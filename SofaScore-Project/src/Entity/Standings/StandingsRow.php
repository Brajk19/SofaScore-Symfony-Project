<?php


namespace App\Entity\Standings;


use App\Entity\AbstractPrimaryEntity;
use App\Entity\Competitor\Competitor;
use Doctrine\ORM\Mapping as ORM;
use SGH\Comparable\Comparable;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Standings
 * @ORM\Entity(repositoryClass="App\Repository\Standings\StandingsRowRepository")
 * @package App\Entity\Standings
 */
class StandingsRow extends AbstractPrimaryEntity  implements Comparable
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
     * @Groups("extended")
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
     * @Groups("extended")
     * @var int
     */
    private int $matches;

    /**
     * @ORM\Column(type="integer")
     * @Groups("extended")
     * @var int
     */
    private int $wins;

    /**
     * @ORM\Column(type="integer")
     * @Groups("extended")
     * @var int
     */
    private int $losses;

    /**
     * @ORM\Column(type="integer")
     * @Groups("extended")
     * @var int
     */
    private int $scoresFor;

    /**
     * @ORM\Column(type="integer")
     * @Groups("extended")
     * @var int
     */
    private int $scoresAgainst;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("extended")
     * @var int|null
     */
    private ?int $draws;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("extended")
     * @var int|null
     */
    private ?int $points;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups("extended")
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

        $sportName = $standings->getSeason()->getCompetition()->getCategory()->getSport()->getName();
        if($sportName === "Football"){
            $this->setDraws(0);
            $this->setPoints(0);
        }
        else if($sportName === "Basketball"){
            $this->setWinPercentage(0);
        }
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



    public function compareTo($object): int
    {
        $sport = $this->getStandings()->getSeason()->getCompetition()->getCategory()->getSport();

        if($sport->getName() === "Football"){
            if($this->getPoints() > $object->getPoints()) return 1;
            else if($this->getPoints() < $object->getPoints()) return -1;
            else {
                $row1goalDifference = $this->getScoresFor() - $this->getScoresAgainst();
                $row2goalDifference = $object->getScoresFor() - $object->getScoresAgainst();

                if($row1goalDifference > $row2goalDifference) return 1;
                else if($row1goalDifference < $row2goalDifference) return -1;
                else return 0;
            }
        }
        else{
            if($this->getWins() > $object->getWins()) return 1;
            else if($this->getWins() < $object->getWins()) return -1;
            else{
                $row1difference = $this->getScoresFor() - $this->getScoresAgainst();
                $row2difference = $object->getScoresFor() - $object->getScoresAgainst();

                if($row1difference > $row2difference) return 1;
                else if($row1difference < $row2difference) return -1;
                else return 0;
            }
        }
    }



}