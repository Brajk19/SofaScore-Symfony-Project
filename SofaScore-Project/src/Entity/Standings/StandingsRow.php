<?php


namespace App\Entity\Standings;


use App\Entity\AbstractPrimaryEntity;
use App\Entity\Competitor\Competitor;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Standings
 * @ORM\Entity(repositoryClass="App\Repository\Standings\StandingsRowRepository")
 * @package App\Entity\Standings
 */
class StandingsRow extends AbstractPrimaryEntity
{

    public function __construct()
    {
        $this->matches = 0;
        $this->wins = 0;
        $this->losses = 0;
        $this->scoresFor = 0;
        $this->scoresAgainst = 0;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Competitor::class)
     * @var Competitor
     */
    private Competitor $competitor;

    /**
     * @ORM\ManyToOne(targetEntity=Standings::class)
     * @var Standings
     */
    private Standings $standings;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $matches;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $wins;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $losses;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $scoresFor;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $scoresAgainst;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private ?int $draws;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private ?int $points;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @var float|null
     */
    private ?float $winPercentage;

    /**
     * @return Competitor
     */
    public function getCompetitor(): Competitor
    {
        return $this->competitor;
    }

    /**
     * @param Competitor $competitor
     */
    public function setCompetitor(Competitor $competitor): void
    {
        $this->competitor = $competitor;
    }

    /**
     * @return Standings
     */
    public function getStandings(): Standings
    {
        return $this->standings;
    }

    /**
     * @param Standings $standings
     */
    public function setStandings(Standings $standings): void
    {
        $this->standings = $standings;
    }

    /**
     * @return int
     */
    public function getMatches(): int
    {
        return $this->matches;
    }

    /**
     * @param int $matches
     */
    public function setMatches(int $matches): void
    {
        $this->matches = $matches;
    }

    /**
     * @return int
     */
    public function getWins(): int
    {
        return $this->wins;
    }

    /**
     * @param int $wins
     */
    public function setWins(int $wins): void
    {
        $this->wins = $wins;
    }

    /**
     * @return int
     */
    public function getLosses(): int
    {
        return $this->losses;
    }

    /**
     * @param int $losses
     */
    public function setLosses(int $losses): void
    {
        $this->losses = $losses;
    }

    /**
     * @return int
     */
    public function getScoresFor(): int
    {
        return $this->scoresFor;
    }

    /**
     * @param int $scoresFor
     */
    public function setScoresFor(int $scoresFor): void
    {
        $this->scoresFor = $scoresFor;
    }

    /**
     * @return int
     */
    public function getScoresAgainst(): int
    {
        return $this->scoresAgainst;
    }

    /**
     * @param int $scoresAgainst
     */
    public function setScoresAgainst(int $scoresAgainst): void
    {
        $this->scoresAgainst = $scoresAgainst;
    }

    /**
     * @return int|null
     */
    public function getDraws(): ?int
    {
        return $this->draws;
    }

    /**
     * @param int|null $draws
     */
    public function setDraws(?int $draws): void
    {
        $this->draws = $draws;
    }

    /**
     * @return int|null
     */
    public function getPoints(): ?int
    {
        return $this->points;
    }

    /**
     * @param int|null $points
     */
    public function setPoints(?int $points): void
    {
        $this->points = $points;
    }

    /**
     * @return float|null
     */
    public function getWinPercentage(): ?float
    {
        return $this->winPercentage;
    }

    /**
     * @param float|null $winPercentage
     */
    public function setWinPercentage(?float $winPercentage): void
    {
        $this->winPercentage = $winPercentage;
    }



}