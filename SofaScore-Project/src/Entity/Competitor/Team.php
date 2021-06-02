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

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private string $type = "team";

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        //
        $this->type = $type;
    }
}