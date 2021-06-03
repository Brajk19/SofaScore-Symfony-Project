<?php


namespace App\Entity\Competitor;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Person
 * @ORM\Entity()
 * @package App\Entity
 */
class Person extends Competitor
{
    public function getType(): string
    {
        return "person";
    }
}