<?php

namespace App\Entity\Competitor;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Team
 * @ORM\Entity()
 * @package App\Entity
 */
class Team extends Competitor
{

    public function getType(): string
    {
        return "team";
    }
}