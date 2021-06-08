<?php


namespace App\Entity\Category;


use App\Entity\AbstractPrimaryEntity;
use App\Entity\Sport\Sport;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @ORM\Entity(repositoryClass="App\Repository\Category\CategoryRepository")
 * @package App\Entity\Category
 */
class Category extends AbstractPrimaryEntity
{

    /**
     * @ORM\ManyToOne(targetEntity=Sport::class)
     * @var Sport
     */
    private Sport $sport;


    /**
     * @return Sport
     */
    public function getSport(): Sport
    {
        return $this->sport;
    }

    /**
     * @param Sport $sport
     */
    public function setSport(Sport $sport): void
    {
        $this->sport = $sport;
    }

    public function __toString(): string
    {
        return $this->getName();
    }


}