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


    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private bool $matchFinished;

    public function __construct(int $numberOfMatchPeriods)
    {
        $this->score = [];
        $this->matchFinished = false;

        for($i = 1; $i <= $numberOfMatchPeriods; $i++){
            $this->score[$i] = null;
        }
    }


    public function endMatch(): void
    {
        $this->matchFinished = true;
    }

    public function startOvertime(): void
    {
        $this->score[count($this->score) + 1] = 0;
    }


    public function setOvertime(int $score): void
    {
        $this->score[count($this->score)] = $score;
    }

    public function setScore(int $period, int $score): void
    {
        $this->score[$period] = $score;
    }


    public function getScore(): array
    {
        return $this->score;
    }

}
