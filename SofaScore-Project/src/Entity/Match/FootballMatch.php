<?php


namespace App\Entity\Match;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class FootballMatch
 * @ORM\Entity()
 * @package App\Entity\Match
 */
class FootballMatch extends AbstractMatch
{
    public function __construct()
    {
        parent::__construct(2);
    }
}