<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AbstractPrimaryEntity
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    protected ?string $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    protected ?string $slug;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->setSlug();
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }


    private function setSlug(): void
    {
        //automatically set - depends on name

        $slugger = new AsciiSlugger();
        $this->slug = $slugger->slug($this->getName());
    }
}