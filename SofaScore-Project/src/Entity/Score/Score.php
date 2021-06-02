<?php

namespace App\Entity\Score;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Score
 * @ORM\Embeddable()
 * @package App\Entity\Score
 */
class Score
{

    /**
     * @ORM\Column(type="simple_array")
     * @var array
     */
    private array $score;

    public function __construct(int $numberOfMatchIntervals)
    {
        $this->score = [];

        for($i = 1; $i <= $numberOfMatchIntervals; $i++){
            $this->score[$i] = null;
        }
    }

    public function setScore(int $matchPart, int $score): void
    {
        $this->score[$matchPart] = $score;
    }

    public function increaseScore(int $matchPart, int $increaseBy): void
    {
        if(is_null($this->score[$matchPart])){
            $this->score[$matchPart] = $increaseBy;
        }
        else{
            $this->score[$matchPart] += $increaseBy;
        }

    }

    public function getScore(): array
    {
        return $this->score;
    }
}
