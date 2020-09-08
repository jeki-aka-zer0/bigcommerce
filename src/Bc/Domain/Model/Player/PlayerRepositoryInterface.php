<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Player;

interface PlayerRepositoryInterface
{
    public function findBySubscriberId(int $subscriberId): ?Player;

    /**
     * @param int $subscriberId
     *
     * @return Player
     * @throws PlayerNotFoundException
     */
    public function getBySubscriberId(int $subscriberId): Player;

    public function add(Player $player): void;
}
