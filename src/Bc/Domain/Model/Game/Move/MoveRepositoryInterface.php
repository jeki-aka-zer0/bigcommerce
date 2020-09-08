<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game\Move;

interface MoveRepositoryInterface
{
    public function add(Move $move): void;
}
