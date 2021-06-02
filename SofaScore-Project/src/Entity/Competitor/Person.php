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
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private string $type = "person";


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
        $this->type = $type;

    }
}