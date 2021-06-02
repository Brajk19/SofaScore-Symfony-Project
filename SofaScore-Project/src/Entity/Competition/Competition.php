<?php

namespace App\Entity\Competition;

use App\Entity\Category\Category;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Competition
 * @ORM\Entity(repositoryClass="App\Repository\Competition\CompetitionRepository")
 * @package App\Entity\Competition
 */
class Competition extends \App\Entity\AbstractPrimaryEntity
{

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @var Category
     */
    private Category $category;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $roundRobinMatches;

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getRoundRobinMatches(): int
    {
        return $this->roundRobinMatches;
    }

    /**
     * @param int $roundRobinMatches
     */
    public function setRoundRobinMatches(int $roundRobinMatches): void
    {
        $this->roundRobinMatches = $roundRobinMatches;
    }


}