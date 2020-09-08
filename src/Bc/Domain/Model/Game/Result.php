<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game;

final class Result
{
    private int $bulls;

    private int $cows;

    private ?int $movesLeft;

    public function __construct(int $bulls, int $cows)
    {
        $this->bulls = $bulls;
        $this->cows = $cows;
    }

    public function getBulls(): int
    {
        return $this->bulls;
    }

    public function getCows(): int
    {
        return $this->cows;
    }

    public function setMovesLeft(?int $movesLeft): void
    {
        $this->movesLeft = $movesLeft;
    }

    public function getMovesLeft(): ?int
    {
        return $this->movesLeft;
    }

    public function isVictory(): bool
    {
        return Figures::LENGTH === $this->getBulls();
    }

    public function toArray(): array
    {
        return [
            'is_victory' => $this->isVictory(),
            'bulls' => $this->getBulls(),
            'cows' => $this->getCows(),
            'moves_left' => $this->getMovesLeft(),
            'errors' => '',
        ];
    }
}
