<?php

declare(strict_types=1);

namespace Src\Bc\Application\Game\Move;

final class Command
{
    private int $subscriberId;

    private string $figures;

    public function __construct(int $subscriberId, string $figures)
    {
        $this->subscriberId = $subscriberId;
        $this->figures = $figures;
    }

    public function getSubscriberId(): int
    {
        return $this->subscriberId;
    }

    public function getFigures(): string
    {
        return $this->figures;
    }
}
