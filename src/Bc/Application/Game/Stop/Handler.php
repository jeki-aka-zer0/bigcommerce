<?php

declare(strict_types=1);

namespace Src\Bc\Application\Game\Stop;

use Src\Bc\Application\RuntimeException;
use Src\Bc\Domain\Model\FlusherInterface;
use Src\Bc\Domain\Model\Game\GameRepositoryInterface;
use Src\Bc\Domain\Model\Player\PlayerNotFoundException;
use Src\Bc\Domain\Model\Player\PlayerRepositoryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class Handler
{
    private PlayerRepositoryInterface $players;

    private GameRepositoryInterface $games;

    private FlusherInterface $flusher;

    private TranslatorInterface $translator;

    public function __construct(
        PlayerRepositoryInterface $players,
        GameRepositoryInterface $games,
        FlusherInterface $flusher,
        TranslatorInterface $translator
    ) {
        $this->players = $players;
        $this->games = $games;
        $this->flusher = $flusher;
        $this->translator = $translator;
    }

    /**
     * @param Command $command
     *
     * @throws RuntimeException
     */
    public function handle(Command $command): void
    {
        try {
            $player = $this->players->getBySubscriberId($command->getSubscriberId());    
        } catch (PlayerNotFoundException $e) {
            throw new RuntimeException($this->translator->trans($e->getMessage()), $e->getCode(), $e);
        }
        
        $game = $this->games->findNewByPlayerId($player->getId());
        if (null !== $game) {
            $game->loose();

            $this->flusher->flush($game);
        }
    }
}
