<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Domain\Model\Game\Move;

use Src\Bc\Domain\Model\Game\Move\Move;
use Src\Bc\Domain\Model\Game\Move\MoveRepositoryInterface;

final class MemoryMoveRepository implements MoveRepositoryInterface
{
    private array $moves = [];

    public function add(Move $move): void
    {
        $this->moves[$move->getId()->getId()] = $move;
    }
}
