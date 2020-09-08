<?php

declare(strict_types=1);

namespace Src\Bc\Application\Player\Register;

use Exception;
use Src\Bc\Domain\Model\Id;
use Src\Bc\Domain\Model\FlusherInterface;
use Src\Bc\Domain\Model\Player\Player;
use Src\Bc\Domain\Model\Player\PlayerRepositoryInterface;

final class Handler
{
    private PlayerRepositoryInterface $players;

    private FlusherInterface $flusher;

    public function __construct(PlayerRepositoryInterface $players, FlusherInterface $flusher)
    {
        $this->players = $players;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     *
     * @throws Exception
     */
    public function handle(Command $command): void
    {
        $player = $this->players->findBySubscriberId($command->getSubscriberId());

        if (null === $player) {
            $player = new Player(Id::next(), $command->getSubscriberId(), $command->getName());

            $this->players->add($player);

            $this->flusher->flush($player);
        }
    }
}
