<?php


namespace App\Command;


use App\Entity\Category\Category;
use App\Entity\Competition\Competition;
use App\Entity\Competitor\Competitor;
use App\Entity\Match\BasketballMatch;
use App\Entity\Match\FootballMatch;
use App\Entity\Season\Season;
use App\Entity\Sport\Sport;
use App\Entity\Standings\Standings;
use App\Entity\Standings\StandingsRow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class playMatchCommand extends Command
{
    private EntityManagerInterface $entityManager;

    /* HALFTIME GOALS
     * 39.1% chance for 0 goals
     * 34.7% chance for 1 goal
     * 13% chance for 2 goals
     * 8.6% chance for 3 goals
     * 4.3% chance for 4 goals
     */
    private array $footballScores = [4, 3, 3, 2, 2, 2, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0];

    //basketball score will just be random integers in [10, 40]

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;

        parent::__construct("play:match");
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper("question");


        //choose sport
        $allSports = $this->entityManager->getRepository(Sport::class)->findAll();

        $sportQuestion = new ChoiceQuestion("Select sport: ", $allSports, 0);
        $sportQuestion->setErrorMessage("Invalid sport '%s'");

        /**
         * @var Sport $sport
         */
        $sport = $helper->ask($input, $output, $sportQuestion);
        $output->writeln("\nYou have selected: " . $sport);



        //choose category
        $categories = $this->entityManager->getRepository(Category::class)->findBy(["sport" => $sport]);
        $categoryQuestion = new ChoiceQuestion("\n\nSelect category: ", $categories, 0);
        $sportQuestion->setErrorMessage("Invalid category '%s'");

        $category = $helper->ask($input, $output, $categoryQuestion);
        $output->writeln("\nYou have selected: " . $category);


        //choose competition
        $competitions = $this->entityManager->getRepository(Competition::class)->findBy(["category" => $category]);
        $competitionQuestion = new ChoiceQuestion("\n\nSelect competition: ", $competitions, 0);
        $competitionQuestion->setErrorMessage("Invalid competition '%s");

        $competition = $helper->ask($input, $output, $competitionQuestion);
        $output->writeln("\nYou have selected: " . $competition);


        //choose season
        $seasons = $this->entityManager->getRepository(Season::class)->findBy(["competition" => $competition]);
        $seasonQuestion = new ChoiceQuestion("\n\nSelect season: ", $seasons, 0);
        $seasonQuestion->setErrorMessage("Invalid season '%s'");

        $season = $helper->ask($input, $output, $seasonQuestion);
        $output->writeln("\nYou have selected: " . $season);


        ////////////////////////////////////////////////////
        /// INPUT DONE
        /////////////////////////////////////////////////////

        while(true){

            //finding next unfinished match
            switch ($sport->getName()){
                case "Football":
                    $match = $this->entityManager->getRepository(FootballMatch::class)->findBy(["competition" => $competition,
                        "season" => $season, "winnerCode" => -1], ["startTime" => "ASC"], 1);
                    $periods = 2;
                    break;

                case "Basketball":
                    $match = $this->entityManager->getRepository(BasketballMatch::class)->findBy(["competition" => $competition,
                        "season" => $season, "winnerCode" => -1], ["startTime" => "ASC"], 1);
                    $periods = 4;
                    break;
            }

            if(count($match) === 0){
                $output->writeln("All matches have been played.");
                return Command::SUCCESS;
            }

            /**
             * @var FootballMatch|BasketballMatch $match
             */
            $match = $match[0];

            $homeCompetitor= $match->getHomeCompetitor();
            $awayCompetitor = $match->getAwayCompetitor();

            $output->writeln(["\nNext match: {$homeCompetitor->getName()} - {$awayCompetitor->getName()}",
                "Start time: {$match->getStartTime()->format('j. F Y. H:i')}"]);

            for($i = 1; $i <= $periods; $i++){
                $match->setHomeScore($i, $this->getRandomScore($sport));
                $match->setAwayScore($i, $this->getRandomScore($sport));
            }

            if(array_sum($match->getHomeScore()->getScore()) === array_sum($match->getAwayScore()->getScore()) && $sport->getName() === "Basketball"){
                $match->getHomeScore()->startOvertime();
                $match->getAwayScore()->startOvertime();

                $a = $this->getRandomScore($sport);
                $b = $this->getRandomScore($sport);
                while($a === $b){
                    $a = $this->getRandomScore($sport);
                    $b = $this->getRandomScore($sport);
                }

                $match->getHomeScore()->setOvertime($a);
                $match->getAwayScore()->setOvertime($b);
            }

            $match->finishMatch();


            //getting standings row - total
            $standingsTotal = $this->entityManager->getRepository(Standings::class)->findOneBy(["season" => $season,
                "type" => "total"]);
            $standingsHome = $this->entityManager->getRepository(Standings::class)->findOneBy(["season" => $season,
                "type" => "home"]);
            $standingsAway = $this->entityManager->getRepository(Standings::class)->findOneBy(["season" => $season,
                "type" => "away"]);


            //malo nespretno nazivlje za sljedece objekte :/
            /**
             * @var StandingsRow $standingsRowTotalHome
             */
            $standingsRowTotalHome = $this->entityManager->getRepository(StandingsRow::class)
                ->findOneBy(["competitor" => $homeCompetitor, "standings" => $standingsTotal]);

            /**
             * @var StandingsRow $standingsRowTotalAway
             */
            $standingsRowTotalAway = $this->entityManager->getRepository(StandingsRow::class)
                ->findOneBy(["competitor" => $awayCompetitor, "standings" => $standingsTotal]);

            $standingsRowHome = $this->entityManager->getRepository(StandingsRow::class)
                ->findOneBy(["competitor" => $homeCompetitor, "standings" => $standingsHome]);
            $standingsRowAway = $this->entityManager->getRepository(StandingsRow::class)
                ->findOneBy(["competitor" => $awayCompetitor, "standings" => $standingsAway]);

            $standingsRowTotalHome->setMatches($standingsRowTotalHome->getMatches() + 1);
            $standingsRowTotalAway->setMatches($standingsRowTotalAway->getMatches() + 1);
            $standingsRowHome->setMatches($standingsRowHome->getMatches() + 1);
            $standingsRowAway->setMatches($standingsRowAway->getMatches() + 1);

            $homeFinalScore = array_sum($match->getHomeScore()->getScore());
            $awayFinalScore = array_sum($match->getAwayScore()->getScore());

            $standingsRowTotalHome->setScoresFor($standingsRowTotalHome->getScoresFor() + $homeFinalScore);
            $standingsRowTotalAway->setScoresFor($standingsRowTotalAway->getScoresFor() + $awayFinalScore);
            $standingsRowTotalHome->setScoresAgainst($standingsRowTotalHome->getScoresAgainst() + $awayFinalScore);
            $standingsRowTotalAway->setScoresAgainst($standingsRowTotalAway->getScoresAgainst() + $homeFinalScore);

            $standingsRowHome->setScoresFor($standingsRowHome->getScoresFor() + $homeFinalScore);
            $standingsRowHome->setScoresAgainst($standingsRowHome->getScoresAgainst() + $awayFinalScore);
            $standingsRowAway->setScoresFor($standingsRowAway->getScoresFor() + $awayFinalScore);
            $standingsRowAway->setScoresAgainst($standingsRowAway->getScoresAgainst() + $homeFinalScore);

            if($homeFinalScore > $awayFinalScore){
                $match->setWinnerCode(1);
                $standingsRowTotalHome->setWins($standingsRowTotalHome->getWins() + 1);
                $standingsRowTotalAway->setLosses($standingsRowTotalAway->getLosses() + 1);
                $standingsRowHome->setWins($standingsRowHome->getWins() + 1);
                $standingsRowAway->setLosses($standingsRowAway->getLosses() + 1);

                if($sport->getName() === "Football"){
                    $standingsRowTotalHome->setPoints($standingsRowTotalHome->getPoints() + 3);
                    $standingsRowHome->setPoints($standingsRowHome->getPoints() + 3);
                }
            }
            else if($homeFinalScore < $awayFinalScore){
                $match->setWinnerCode(2);
                $standingsRowTotalHome->setLosses($standingsRowTotalHome->getLosses() + 1);
                $standingsRowTotalAway->setWins($standingsRowTotalAway->getWins() + 1);
                $standingsRowHome->setLosses($standingsRowHome->getLosses() + 1);
                $standingsRowAway->setWins($standingsRowAway->getWins() + 1);

                if($sport->getName() === "Football"){
                    $standingsRowTotalAway->setPoints($standingsRowTotalAway->getPoints() + 3);
                    $standingsRowAway->setPoints($standingsRowAway->getPoints() + 3);
                }
            }
            else{
                $match->setWinnerCode(3);
                $standingsRowTotalHome->setDraws($standingsRowTotalHome->getDraws() + 1);
                $standingsRowTotalAway->setDraws($standingsRowTotalAway->getDraws() + 1);

                $standingsRowTotalHome->setPoints($standingsRowTotalHome->getPoints() + 1);
                $standingsRowTotalAway->setPoints($standingsRowTotalAway->getPoints() + 1);


                $standingsRowAway->setDraws($standingsRowAway->getDraws() + 1);
                $standingsRowHome->setDraws($standingsRowHome->getDraws() + 1);

                $standingsRowAway->setPoints($standingsRowAway->getPoints() + 1);
                $standingsRowHome->setPoints($standingsRowHome->getPoints() + 1);
            }

            if($sport->getName() === "Basketball"){
                $standingsRowTotalHome->setWinPercentage($standingsRowTotalHome->getWins() / $standingsRowTotalHome->getMatches());
                $standingsRowTotalAway->setWinPercentage($standingsRowTotalAway->getWins() / $standingsRowTotalAway->getMatches());

                $standingsRowHome->setWinPercentage($standingsRowHome->getWins() / $standingsRowHome->getMatches());
                $standingsRowAway->setWinPercentage($standingsRowAway->getWins() / $standingsRowAway->getMatches());
            }




            $this->entityManager->persist($match);
            $this->entityManager->flush();


            $output->writeln("Match score: {$homeFinalScore}:{$awayFinalScore}");

            $question = new ConfirmationQuestion("\nPlay another match? (y/n): ", true);

            if (!$helper->ask($input, $output, $question)) {
                break;
            }
        }


        return Command::SUCCESS;
    }


    private function getRandomScore(Sport $sport): int
    {
        switch ($sport->getName()){
            case "Football":
                shuffle($this->footballScores);
                return $this->footballScores[0];

            case "Basketball":
                return rand(10, 40);
        }
    }
}