<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Domain\Model\Player;

use Src\Bc\Domain\Model\Player\PlayerNotFoundException;
use Src\Bc\Domain\Model\Player\Player;
use Src\Bc\Domain\Model\Player\PlayerRepositoryInterface;

final class MemoryPlayerRepository implements PlayerRepositoryInterface
{
    private array $players = [];

    public function findBySubscriberId(int $subscriberId): ?Player
    {
        return $this->players[$subscriberId] ?? null;
    }

    /**
     * @param int $subscriberId
     *
     * @return Player
     * @throws PlayerNotFoundException
     */
    public function getBySubscriberId(int $subscriberId): Player
    {
        $player = $this->findBySubscriberId($subscriberId);
        if (null === $player) {
            throw new PlayerNotFoundException('Player not found.');
        }

        return $player;
    }

    public function add(Player $player): void
    {
        $this->players[$player->getSubscriberId()] = $player;
    }
}
