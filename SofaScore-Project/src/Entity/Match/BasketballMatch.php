<?php


namespace App\Entity\Match;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class BasketballMatch
 * @ORM\Entity()
 * @package App\Entity\Match
 */
class BasketballMatch extends AbstractMatch
{
    public function __construct()
    {
        parent::__construct(4);
    }
}