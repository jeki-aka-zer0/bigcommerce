<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game\Score;

interface ScoreRepositoryInterface
{
    public function getScore(int $subscriberId): ?array;

    public function getTop(): array;
}
