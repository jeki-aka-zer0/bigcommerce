<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game;

use Src\Bc\Domain\Model\Id;

interface GameRepositoryInterface
{
    public function findNewByPlayerId(Id $playerId): ?Game;

    /**
     * @param Id $playerId
     *
     * @return Game
     * @throws GameNotFoundException
     */
    public function getNewByPlayerId(Id $playerId): Game;

    public function add(Game $game): void;
}
