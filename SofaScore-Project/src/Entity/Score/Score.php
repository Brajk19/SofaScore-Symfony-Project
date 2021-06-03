<?php

namespace App\Entity\Score;

use Doctrine\ORM\Mapping as ORM;
use exception\MatchFinishedException;

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
     * @ORM\Column(type="integer")
     * @var int
     */
    private int $currentPeriod;


    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private bool $matchFinished;

    public function __construct(int $numberOfMatchPeriods)
    {
        $this->score = [];
        $this->currentPeriod = 0;   //match hasn't started yet
        $this->matchFinished = false;

        for($i = 1; $i <= $numberOfMatchPeriods; $i++){
            $this->score[$i] = null;
        }
    }

    /**
     * Switches to next period of a match.
     * @return bool
     * Returns false if match has ended.
     * Returns true otherwise.
     * @throws MatchFinishedException if match is already concluded.
     */
    public function nextPeriod(): bool {
        if($this->matchFinished){
            throw new MatchFinishedException();
        }

        $this->currentPeriod++;

        if(!array_key_exists($this->currentPeriod, $this->score)){
            $this->endMatch();
            return false;
        }

        $this->setScore(0);
        return true;
    }

    public function startMatch(): void
    {
        if($this->currentPeriod === 0) {
            $this->currentPeriod = 1;
        }
    }

    public function endMatch(): void
    {
        $this->matchFinished = true;
    }

    public function startOvertime(): void
    {
        $this->currentPeriod++;
        $this->score[$this->currentPeriod] = 0;
    }

    public function setScore(int $score): void
    {
        $this->score[$this->currentPeriod] = $score;
    }

    public function increaseScore(int $increaseBy): void
    {
        $this->score[$this->currentPeriod] += $increaseBy;
    }

    public function getScore(): array
    {
        return $this->score;
    }

    public function getCurrentPeriodScore(): int
    {
        return $this->score[$this->currentPeriod];
    }
}
