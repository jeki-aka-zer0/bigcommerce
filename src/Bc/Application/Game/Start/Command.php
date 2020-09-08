<?php

declare(strict_types=1);

namespace Src\Bc\Application\Game\Start;

final class Command
{
    private int $subscriberId;

    private string $level;

    public function __construct(int $subscriberId, string $level)
    {
        $this->subscriberId = $subscriberId;
        $this->level = $level;
    }

    public function getSubscriberId(): int
    {
        return $this->subscriberId;
    }

    public function getLevel(): string
    {
        return $this->level;
    }
}
