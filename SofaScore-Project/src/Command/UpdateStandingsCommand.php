<?php


namespace App\Command;

use App\Entity\Match\AbstractMatch;
use App\Entity\Standings\Standings;
use App\Entity\Standings\StandingsRow;
use App\Service\Listener\MatchScoreChangeListener;
use App\Service\Update\StandingsRowUpdate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

class UpdateStandingsCommand extends Command
{
    private StandingsRowUpdate $rowUpdate;
    private EntityManagerInterface $entityManager;
    private ClientInterface $redis;

    public function __construct(EntityManagerInterface $em, StandingsRowUpdate $rowUpdate, ClientInterface $redis)
    {
        $this->entityManager = $em;
        $this->rowUpdate = $rowUpdate;
        $this->redis = $redis;

        parent::__construct("update:standings");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $matchID = $this->redis->pop(MatchScoreChangeListener::SCORE_CHANGE_QUEUE_KEY);

        if(is_null($matchID)){
            echo "All standings are up-to-date.\n";
            return Command::SUCCESS;
        }

        while(!is_null($matchID)){

            /**
             * @var AbstractMatch $match
             */
            $match = $this->entityManager->getRepository(AbstractMatch::class)->find($matchID);

            $homeCompetitor = $match->getHomeCompetitor();
            $awayCompetitor = $match->getAwayCompetitor();
            $season = $match->getSeason();
            $competition = $match->getCompetition();

            $standingsTotal = $this->entityManager->getRepository(Standings::class)->findOneBy([
                "type" => "total", "season" => $season]);
            $standingsAway = $this->entityManager->getRepository(Standings::class)->findOneBy([
                "type" => "away", "season" => $season]);
            $standingsHome = $this->entityManager->getRepository(Standings::class)->findOneBy([
                "type" => "home", "season" => $season]);

            /**
             * @var StandingsRow $standingsRowTotal1
             */
            $standingsRowTotal1 = $this->entityManager->getRepository(StandingsRow::class)->findOneBy([
                "competitor" => $homeCompetitor, "standings" => $standingsTotal]);

            /**
             * @var StandingsRow $standingsRowTotal2
             */
            $standingsRowTotal2 = $this->entityManager->getRepository(StandingsRow::class)->findOneBy([
                "competitor" => $awayCompetitor, "standings" => $standingsTotal]);

            /**
             * @var StandingsRow $standingsRowHome
             */
            $standingsRowHome = $this->entityManager->getRepository(StandingsRow::class)->findOneBy([
                "competitor" => $homeCompetitor, "standings" => $standingsHome]);

            /**
             * @var StandingsRow $standingsRowAway
             */
            $standingsRowAway = $this->entityManager->getRepository(StandingsRow::class)->findOneBy([
                "competitor" => $awayCompetitor, "standings" => $standingsAway]);


            $this->rowUpdate->updateHomeCompetitor($homeCompetitor, $standingsRowTotal1, $standingsRowHome);
            $this->rowUpdate->updateAwayCompetitor($awayCompetitor, $standingsRowTotal2, $standingsRowAway);

            $output->writeln("Standings updated for match: " . $match->getName());
            $matchID = $this->redis->pop(MatchScoreChangeListener::SCORE_CHANGE_QUEUE_KEY);
        }



        return Command::SUCCESS;
    }
}