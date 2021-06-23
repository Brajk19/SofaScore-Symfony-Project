<?php

namespace App\Service\Update;

use App\Entity\Competitor\Competitor;
use App\Entity\Match\AbstractMatch;
use App\Entity\Sport\Sport;
use App\Entity\Standings\StandingsRow;
use Doctrine\ORM\EntityManagerInterface;

class StandingsRowUpdate
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateHomeCompetitor(Competitor $competitor, StandingsRow $total, StandingsRow $home): void
    {
        $season = $total->getStandings()->getSeason();
        $competition = $season->getCompetition();

        //echo strval($total->getScoresFor()) . " " . strval($total->getScoresAgainst()) . " " . strval($total->getLosses()) . "\n";

        /**
         * @var AbstractMatch[] $matchesHome
         */
        $matchesHome = $this->entityManager->getRepository(AbstractMatch::class)->findBy([
            "homeCompetitor" => $competitor, "competition" => $competition, "season" => $season, "statusCode" => 9]);

        /**
         * @var AbstractMatch[] $matchesAway
         */
        $matchesAway = $this->entityManager->getRepository(AbstractMatch::class)->findBy([
            "awayCompetitor" => $competitor, "competition" => $competition, "season" => $season, "statusCode" => 9]);

        $total->setMatches(count($matchesHome) + count($matchesAway));
        $home->setMatches(count($matchesHome));

        $total->setScoresFor(0);
        $total->setScoresAgainst(0);
        $home->setScoresFor(0);
        $home->setScoresAgainst(0);

        $winsTotal = 0;
        $winsHome = 0;
        $drawsTotal = 0;
        $drawsHome = 0;
        $lossesHome = 0;
        $lossesTotal = 0;

        foreach ($matchesHome as $match){
            if($match->getWinnerCode() === 1){
                $winsHome++;
                $winsTotal++;
            }
            else if($match->getWinnerCode() === 2){
                $lossesHome++;
                $lossesTotal++;
            }
            else{
                $drawsTotal++;
                $drawsHome++;
            }

            $scoresFor = $match->getHomeScore()->getScore();
            $scoresAgainst = $match->getAwayScore()->getScore();

            for($i = 1; $i < count($scoresFor); $i++){
                $total->setScoresFor($total->getScoresFor() + $scoresFor[$i]);
                $total->setScoresAgainst($total->getScoresAgainst() + $scoresAgainst[$i]);
                $home->setScoresFor($home->getScoresFor() + $scoresFor[$i]);
                $home->setScoresAgainst($home->getScoresAgainst() + $scoresAgainst[$i]);
            }
        }

        foreach ($matchesAway as $match){
            if($match->getWinnerCode() === 2){
                $winsTotal++;
            }
            else if($match->getWinnerCode() === 1){
                $lossesTotal++;
            }
            else{
                $drawsTotal++;
            }

            $scoresFor = $match->getAwayScore()->getScore();
            $scoresAgainst = $match->getHomeScore()->getScore();

            for($i = 1; $i < count($scoresFor); $i++){
                $total->setScoresFor($total->getScoresFor() + $scoresFor[$i]);
                $total->setScoresAgainst($total->getScoresAgainst() + $scoresAgainst[$i]);
            }
        }

        $total->setWins($winsTotal);
        $total->setLosses($lossesTotal);
        $home->setWins($winsHome);
        $home->setLosses($lossesTotal);

        if($competition->getCategory()->getSport()->getName() === "Football"){
            $total->setDraws($drawsTotal);
            $total->setPoints($drawsTotal + 3 * $winsTotal);
            $home->setDraws($drawsHome);
            $home->setPoints($drawsHome + 3 * $winsHome);
        }
        else{
            $total->setWinPercentage($winsTotal / $total->getMatches());
            $home->setWinPercentage($winsHome / $home->getMatches());
        }

        $this->entityManager->persist($total);
        $this->entityManager->persist($home);
        $this->entityManager->flush();
        //echo strval($total->getScoresFor()) . " " . strval($total->getScoresAgainst()) . " " . strval($total->getLosses()) . "\n";
    }


    public function updateAwayCompetitor(Competitor $competitor, StandingsRow $total, StandingsRow $away): void
    {
        $this->updateHomeCompetitor($competitor, $total, $away);
    }
}