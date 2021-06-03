<?php


namespace App\Command;


use App\Entity\Category\Category;
use App\Entity\Competition\Competition;
use App\Entity\Competitor\Competitor;
use App\Entity\Competitor\Team;
use App\Entity\Season\Season;
use App\Entity\Sport\Sport;
use App\Entity\Standings\Standings;
use App\Entity\Standings\StandingsRow;
use App\Service\Helper\DummyDataHelper;
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
        $teamNames = DummyDataHelper::getRandomCompetitorNames(rand(10, 16));
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
        //TODO
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

}