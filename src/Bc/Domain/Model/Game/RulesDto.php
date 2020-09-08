<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game;

final class RulesDto
{
    private int $maxMovesCountForHardLevel;

    private int $pointsForHardVictory;

    private int $pointsForEasyVictory;

    private int $pointsForLosing;

    private int $scoreBoardSize;

    public function __construct(
        int $maxMovesCountForHardLevel,
        int $pointsForHardVictory,
        int $pointsForEasyVictory,
        int $pointsForLosing,
        int $scoreBoardSize
    ) {
        $this->maxMovesCountForHardLevel = $maxMovesCountForHardLevel;
        $this->pointsForHardVictory = $pointsForHardVictory;
        $this->pointsForEasyVictory = $pointsForEasyVictory;
        $this->pointsForLosing = $pointsForLosing;
        $this->scoreBoardSize = $scoreBoardSize;
    }

    public function getMaxMovesCountForHardLevel(): int
    {
        return $this->maxMovesCountForHardLevel;
    }

    public function getPointsForHardVictory(): int
    {
        return $this->pointsForHardVictory;
    }

    public function getPointsForEasyVictory(): int
    {
        return $this->pointsForEasyVictory;
    }

    public function getPointsForLosing(): int
    {
        return $this->pointsForLosing;
    }

    public function getScoreBoardSize(): int
    {
        return $this->scoreBoardSize;
    }
}
