<?php

namespace App\Entity\Match;


use App\Entity\AbstractPrimaryEntity;
use App\Entity\Competition\Competition;
use App\Entity\Competitor\Competitor;
use App\Entity\Score\Score;
use App\Entity\Season\Season;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Competitor
 * @ORM\Entity(repositoryClass="App\Repository\Match\MatchRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discrMatch", type="string")
 * @ORM\DiscriminatorMap({
 *  "footballMatch"=FootballMatch::class,
 *  "basketballMatch"=BasketballMatch::class
 * })
 * @ORM\Table(name="match")
 * @package App\Entity\Match
 */
abstract class AbstractMatch extends AbstractPrimaryEntity
{


    /**
     * @ORM\ManyToOne(targetEntity=Competitor::class)
     * @var Competitor
     */
    private Competitor $homeCompetitor;

    /**
     * @ORM\ManyToOne(targetEntity=Competitor::class)
     * @var Competitor
     */
    private Competitor $awayCompetitor;

    /**
     * @ORM\Column(type="datetime");
     * @var DateTime
     */
    private DateTime $startTime;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $statusCode;

    /**
     * @ORM\ManyToOne(targetEntity=Competition::class)
     * @var Competition
     */
    private Competition $competition;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class)
     * @var Season
     */
    private Season $season;

    /**
     * @ORM\Embedded(class="App\Entity\Score\Score")
     * @var Score
     */
    private Score $homeScore;

    /**
     * @ORM\Embedded(class="App\Entity\Score\Score")
     * @var Score
     */
    private Score $awayScore;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     * null - match not finished,
     * 1 - home,
     * 2 - away,
     * 3 - tie
     */
    private ?int $winnerCode;


    public function __construct(int $matchIntervals)
    {
        $this->homeScore = new Score($matchIntervals);
        $this->awayScore = new Score($matchIntervals);
        $this->setStatusCode(0);
        $this->setWinnerCode(-1);
    }

    /**
     * @return Competitor
     */
    public function getHomeCompetitor(): Competitor
    {
        return $this->homeCompetitor;
    }

    /**
     * @param Competitor $homeCompetitor
     */
    public function setHomeCompetitor(Competitor $homeCompetitor): void
    {
        $this->homeCompetitor = $homeCompetitor;
    }

    /**
     * @return Competitor
     */
    public function getAwayCompetitor(): Competitor
    {
        return $this->awayCompetitor;
    }

    /**
     * @param Competitor $awayCompetitor
     */
    public function setAwayCompetitor(Competitor $awayCompetitor): void
    {
        $this->awayCompetitor = $awayCompetitor;
    }

    /**
     * @return DateTime
     */
    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    /**
     * @param DateTime $startTime
     */
    public function setStartTime(DateTime $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return Competition
     */
    public function getCompetition(): Competition
    {
        return $this->competition;
    }

    /**
     * @param Competition $competition
     */
    public function setCompetition(Competition $competition): void
    {
        $this->competition = $competition;
    }

    /**
     * @return Season
     */
    public function getSeason(): Season
    {
        return $this->season;
    }

    /**
     * @param Season $season
     */
    public function setSeason(Season $season): void
    {
        $this->season = $season;
    }

    /**
     * @return Score
     */
    public function getHomeScore(): Score
    {
        return $this->homeScore;
    }

    /**
     * @param int $period
     * @param int $homeScore
     */
    public function setHomeScore(int $period, int $homeScore): void
    {
        $this->homeScore->setScore($period, $homeScore);
    }

    /**
     * @return Score
     */
    public function getAwayScore(): Score
    {
        return $this->awayScore;
    }

    /**
     * @param int $period
     * @param int $awayScore
     */
    public function setAwayScore(int $period, int $awayScore): void
    {
        $this->awayScore->setScore($period, $awayScore);
    }

    /**
     * @return int
     */
    public function getWinnerCode(): int
    {
        return $this->winnerCode;
    }

    /**
     * @param int $winnerCode
     */
    public function setWinnerCode(int $winnerCode): void
    {
        $this->winnerCode = $winnerCode;
    }

    public function finishMatch(): void
    {
        $this->homeScore->endMatch();
        $this->awayScore->endMatch();
        $this->setStatusCode(9);
    }


}