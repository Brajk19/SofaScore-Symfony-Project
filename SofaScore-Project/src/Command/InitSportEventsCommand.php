<?php


namespace App\Command;


use App\Entity\Category\Category;
use App\Entity\Competition\Competition;
use App\Entity\Competitor\Competitor;
use App\Entity\Competitor\Team;
use App\Entity\Match\BasketballMatch;
use App\Entity\Match\FootballMatch;
use App\Entity\Season\Season;
use App\Entity\Sport\Sport;
use App\Entity\Standings\Standings;
use App\Entity\Standings\StandingsRow;
use App\Service\Helper\DummyDataHelper;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitSportEventsCommand extends Command
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;

        parent::__construct("add:dummyData");
    }

    /**
     * Creates category, competition, season, teams, standings and matches for random sport.
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //choosing football of basketball
        $sport = $this->entityManager->getRepository(Sport::class)->getAll();
        shuffle($sport);
        $sport = $sport[0];
        $output->writeln("Sport: " . $sport->getName());


        //choosing category
        $categoryName = DummyDataHelper::getRandomCategoryName();
        $output->writeln("Category: " . $categoryName);

        $category = $this->entityManager->getRepository(Category::class)->categoryCheck($categoryName, $sport);
        $category = empty($category) ? $this->addNewCategory($categoryName, $sport) : $category[0];


        //choosing competition
        $competitionName = ucfirst(DummyDataHelper::getRandomCompetitionName());
        $output->writeln("Competition: " . $competitionName);

        $competition = $this->entityManager->getRepository(Competition::class)->competitionCheck($competitionName, $category);
        $competition = empty($competition) ? $this->addNewCompetition($competitionName, $category) : $competition[0];


        //choosing competitors (teams)
        $numberOfTeams = rand(10, 16);

        $teamNames = DummyDataHelper::getRandomCompetitorNames($numberOfTeams);
        $output->writeln("\nTeams: ");
        $competitors = [];
        foreach ($teamNames as $teamName){
            $output->write($teamName . ", ");

            $competitor  = $this->entityManager->getRepository(Competitor::class)->competitorCheck($teamName, $sport);
            $competitor = empty($competitor) ? $this->addNewTeam($teamName, $sport) : $competitor[0];

            $competitors[] = $competitor;
        }

        //setting up the season
        $year = rand(1950, 2021);
        $seasonName = strval($year);
        $seasonStart = new DateTime();
        $seasonStart->setDate($year, rand(1, 3), rand(1, 28));
        $seasonStart->setTime(20, 30);

        $seasonEnd = new DateTime();
        $seasonEnd->setDate($year, rand(10, 12), rand(1, 28));
        $seasonEnd->setTime(20, 30);

        $season = $this->addNewSeason($seasonName, $seasonStart, $seasonEnd, $competition);
        $output->writeln(["\n\nSeason: " . $season->getName(), "\tSeason start: " . $season->getSeasonStart()->format("j. F Y. H:i"),
            "\tSeason end: " . $season->getSeasonEnd()->format("j. F Y. H:i")]);


        //setting up all standings
        $standingsHome = $this->addNewStandings($season, "home");
        $standingsAway = $this->addNewStandings($season, "away");
        $standingsTotal = $this->addNewStandings($season, "total");


        $output->writeln(["\nStandings:", "\t" . $standingsHome->getName(), "\t" . $standingsAway->getName(),
            "\t" . $standingsTotal->getName()]);


        //setting up standings rows
        $standingsRowHome = [];
        $standingsRowAway = [];
        $standingsRowTotal = [];
        foreach($competitors as $competitor){
            $standingsRowHome[] = $this->addNewStandingsRow($competitor, $standingsHome);
            $standingsRowAway[] = $this->addNewStandingsRow($competitor, $standingsAway);
            $standingsRowTotal[] = $this->addNewStandingsRow($competitor, $standingsTotal);
        }


        //setting up matches
        $roundsTemp = array_fill(0, 100, []); //written representation of all matches by rounds

        for($rr = 0; $rr < $competition->getRoundRobinMatches(); $rr++){
            for($i = 0; $i < count($competitors); $i++){
                for($j = $i + 1; $j < count($competitors); $j++){
                    $stringMatch = "$i $j";

                    for($index = 0; $index < count($roundsTemp); $index++){
                        $found = false;
                        for($k = 0; $k < count($roundsTemp[$index]); $k++){
                            $arr = explode(" ", $roundsTemp[$index][$k]);
                            if(in_array(strval($i), $arr) || in_array(strval($j), $arr)){
                                $found = true;
                                break;
                            }
                        }

                        if(!$found){
                            $roundsTemp[$index][] = $stringMatch;
                            break;
                        }
                    }
                }
            }
        }

        $rounds = [];
        foreach ($roundsTemp as $arrRound){
            if(empty($arrRound)){
                break;
            }
            else{
                $rounds[] = $arrRound;
            }
        }
        shuffle($rounds);


        //setting up date and time of every round
        //there has to be at least 2 days between two rounds (checkDifference())
        $startMinutes = [0, 15, 30, 45];
        $numberOfRounds = count($rounds);

        $roundTimes = [$seasonStart, $seasonEnd];
        while(count($roundTimes) < $numberOfRounds){
            shuffle($startMinutes);

            $time = new DateTime();
            $time->setDate($year, rand(1, 12), rand(1, 28));
            $time->setTime(rand(13, 21), $startMinutes[0]);

            if($time > $seasonStart && $time < $seasonEnd){
                $valid = true;

                foreach($roundTimes as $t){
                    if(!$this->checkDifference($time->diff($t, true))){
                        $valid = false;
                        break;
                    }

                }

                if($valid){
                    $roundTimes[] = $time;
                }
            }
        }

        $output->writeln("\nNumber of rounds: " . count($roundTimes));
        sort($roundTimes);
        for ($i = 0; $i < count($roundTimes); $i++){
            $output->writeln("Round #" . strval($i + 1) . ": " . $roundTimes[$i]->format("H:i  d. F Y."));
        }


        for($i = 0; $i < $numberOfRounds; $i++){
            $matchTime = $roundTimes[$i];

            foreach ($rounds[$i] as $match){
                $teams = explode(" ", $match);
                shuffle($teams);

                $home = $competitors[intval($teams[0])];
                $away = $competitors[intval($teams[1])];

                $this->addNewMatch($home, $away, $matchTime, $competition, $season);

            }
        }

        $output->writeln(["Matches generated.", "Done."]);
        return Command::SUCCESS;
    }

    /**
     * Creates new Category and stores it in database.
     * @param string $name
     * @param Sport $sport
     * @return Category
     * Returns created Category.
     */
    private function addNewCategory(string $name, Sport $sport): Category
    {
        $c = new Category();
        $c->setName($name);
        $c->setSport($sport);

        $this->entityManager->persist($c);
        $this->entityManager->flush();

        return $c;
    }

    private function addNewCompetition(string $name, Category $category): Competition
    {
        $c = new Competition();
        $c->setName($name);
        $c->setCategory($category);
        $c->setRoundRobinMatches(rand(2, 4));

        $this->entityManager->persist($c);
        $this->entityManager->flush();

        return $c;
    }

    private function addNewTeam(string $name, Sport $sport): Team
    {
        $t = new Team();
        $t->setName($name);
        $t->setSport($sport);
        $t->setCountry(DummyDataHelper::getRandomCountry());

        $this->entityManager->persist($t);
        $this->entityManager->flush();

        return $t;
    }

    private function addNewSeason(string $name, DateTime $start, DateTime $end, Competition $competition): Season
    {
        $s = new Season();
        $s->setName($name);
        $s->setCompetition($competition);
        $s->setSeasonStart($start);
        $s->setSeasonEnd($end);

        $this->entityManager->persist($s);
        $this->entityManager->flush();

        return $s;
    }

    private function addNewStandings(Season $season, string $type): Standings
    {
        $s = new Standings();
        $s->setName($season->getCompetition()->getName() . "(" . $season->getName() . ") " . "#$type");
        $s->setSeason($season);
        $s->setType($type);

        $this->entityManager->persist($s);
        $this->entityManager->flush();

        return $s;
    }

    private function addNewStandingsRow(Competitor $competitor, Standings $standings): StandingsRow
    {
        $sr = new StandingsRow();
        $sr->setName($competitor->getName() . "@" . $standings->getName());
        $sr->setCompetitor($competitor);
        $sr->setStandings($standings);

        $this->entityManager->persist($sr);
        $this->entityManager->flush();

        return $sr;
    }


    /**
     * @param DateInterval $d
     * @return bool
     *
     */
    private function checkDifference(DateInterval $d): bool
    {
        if($d->y === 0 && $d->m === 0 && $d->d < 2){
            return false;
        }

        return true;
    }

    private function addNewMatch(Competitor $home, Competitor $away, DateTime $startTime, Competition $competition,
                                 Season $season): void
    {

        $m = null;
        switch ($home->getSport()->getName()){
            case "Football":
                $m = new FootballMatch();
                break;

            case "Basketball":
                $m = new BasketballMatch();
                break;
        }

        $m->setName("{$home->getName()} - {$away->getName()} ({$competition->getName()} {$season->getName()})");
        $m->setHomeCompetitor($home);
        $m->setAwayCompetitor($away);
        $m-> setSeason($season);
        $m->setStartTime($startTime);
        $m->setCompetition($competition);

        $this->entityManager->persist($m);
        $this->entityManager->flush();
    }
}