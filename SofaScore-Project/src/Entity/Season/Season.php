<?php

namespace App\Entity\Season;

use App\Entity\AbstractPrimaryEntity;
use App\Entity\Competition\Competition;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Season\SeasonRepository")
 * Class Season
 */
class Season extends AbstractPrimaryEntity
{

    /**
     * @ORM\Column(type="datetime")
     * @Groups("basic")
     * @var DateTime
     */
    private DateTime $seasonStart;


    /**
     * @ORM\Column(type="datetime")
     * @Groups("basic")
     * @var DateTime
     */
    private DateTime $seasonEnd;

    /**
     * @ORM\ManyToOne(targetEntity=Competition::class)
     * @var Competition
     */
    private Competition $competition;

    /**
     * @return DateTime
     */
    public function getSeasonStart(): DateTime
    {
        return $this->seasonStart;
    }

    /**
     * @param DateTime $seasonStart
     */
    public function setSeasonStart(DateTime $seasonStart): void
    {
        $this->seasonStart = $seasonStart;
    }

    /**
     * @return DateTime
     */
    public function getSeasonEnd(): DateTime
    {
        return $this->seasonEnd;
    }

    /**
     * @param DateTime $seasonEnd
     */
    public function setSeasonEnd(DateTime $seasonEnd): void
    {
        $this->seasonEnd = $seasonEnd;
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

    public function __toString()
    {
        return $this->getName();
    }


}