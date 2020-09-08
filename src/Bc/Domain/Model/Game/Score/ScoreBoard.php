<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game\Score;

use Src\Bc\Domain\Model\Game\RulesDto;

final class ScoreBoard
{
    private RulesDto $rules;

    /**
     * @var Score[]
     */
    private array $scores = [];

    public function __construct(RulesDto $rules)
    {
        $this->rules = $rules;
    }

    public function __toString(): string
    {
        $str = '';

        foreach ($this->scores as $index => $score) {
            $position = $index + 1;

            $str .= $position > $this->rules->getScoreBoardSize()
                ? '...' . PHP_EOL
                : "{$position}. ";

            $str .= "{$score->getName()}: {$score->getScore()}" . PHP_EOL;
        }

        return $str;
    }

    public function isPLayerIn(int $subscriberId): bool
    {
        foreach ($this->scores as $score) {
            if ($score->isBelongsTo($subscriberId)) {
                return true;
            }
        }

        return false;
    }

    public function addRawScores(array $scoresRaw): void
    {
        array_map([$this, 'addRawScore'], $scoresRaw);
    }

    public function addRawScore(array $scoreRaw): void
    {
        $this->scores[] = $this->buildScore($scoreRaw);
    }

    private function buildScore(array $scoreRaw): Score
    {
        return new Score((int)$scoreRaw['subscriberId'], $scoreRaw['name'], $scoreRaw['score']);
    }
}
