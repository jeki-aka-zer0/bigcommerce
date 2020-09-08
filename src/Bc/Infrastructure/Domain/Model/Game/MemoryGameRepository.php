<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Domain\Model\Game;

use Src\Bc\Domain\Model\Game\GameNotFoundException;
use Src\Bc\Domain\Model\Id;
use Src\Bc\Domain\Model\Game\Game;
use Src\Bc\Domain\Model\Game\GameRepositoryInterface;

final class MemoryGameRepository implements GameRepositoryInterface
{
    private array $games = [];

    public function findNewByPlayerId(Id $playerId): ?Game
    {
        return $this->games[$playerId->getId()] ?? null;
    }

    /**
     * @param Id $playerId
     *
     * @return Game
     * @throws GameNotFoundException
     */
    public function getNewByPlayerId(Id $playerId): Game
    {
        $game = $this->findNewByPlayerId($playerId);
        if (null === $game) {
            throw new GameNotFoundException('Game not found.');
        }

        return $game;
    }

    public function add(Game $game): void
    {
        $this->games[$game->getPlayer()->getId()->getId()] = $game;
    }
}
