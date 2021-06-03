<?php


namespace App\Entity\Competitor;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Pair
 * @ORM\Entity()
 * @package App\Entity
 */
class Pair extends Competitor
{
    public function getType(): string
    {
        return "pair";
    }
}