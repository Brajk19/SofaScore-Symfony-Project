<?php

namespace App\Entity\Competitor;

use App\Entity\AbstractPrimaryEntity;
use App\Entity\Country\Country;
use App\Entity\Sport\Sport;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Competitor
 * @ORM\Entity(repositoryClass="App\Repository\Competitor\CompetitorRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "team"=Team::class,
 *  "person"=Person::class,
 *  "pair"=Pair::class
 * })
 * @ORM\Table(name="competitor")
 * @package App\Entity\Competitor
 */
abstract class Competitor extends AbstractPrimaryEntity
{

    /**
     * @ORM\ManyToOne(targetEntity=Sport::class)
     * @var Sport
     */
    private Sport $sport;


    /**
     * @ORM\Embedded(class="App\Entity\Country\Country")
     * @var Country|null
     */
    private ?Country $country;

    public function __construct()
    {
        $this->country = new Country();
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(Sport $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

    abstract function getType();
}