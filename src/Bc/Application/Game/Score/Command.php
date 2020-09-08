<?php

declare(strict_types=1);

namespace Src\Bc\Application\Game\Score;

final class Command
{
    private int $subscriberId;

    public function __construct(int $subscriberId)
    {
        $this->subscriberId = $subscriberId;
    }

    public function getSubscriberId(): int
    {
        return $this->subscriberId;
    }
}
