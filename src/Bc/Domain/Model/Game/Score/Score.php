<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game\Score;

final class Score
{
    private int $subscriberId;

    private string $name;

    private int $score;

    public function __construct(int $subscriberId, string $name, int $score)
    {
        $this->subscriberId = $subscriberId;
        $this->name = $name;
        $this->score = $score;
    }

    public function isBelongsTo(int $subscriberId): bool
    {
        return $this->subscriberId === $subscriberId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getScore(): int
    {
        return $this->score;
    }
}
