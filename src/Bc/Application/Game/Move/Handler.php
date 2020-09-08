<?php

declare(strict_types=1);

namespace Src\Bc\Application\Game\Move;

use Exception;
use InvalidArgumentException;
use Src\Bc\Application\RuntimeException;
use Src\Bc\Domain\Model\Game\GameNotFoundException;
use Src\Bc\Domain\Model\Id;
use Src\Bc\Domain\Model\FlusherInterface;
use Src\Bc\Domain\Model\Game\GameRepositoryInterface;
use Src\Bc\Domain\Model\Game\Result;
use Src\Bc\Domain\Model\Game\RulesDto;
use Src\Bc\Domain\Model\Game\Move\Move;
use Src\Bc\Domain\Model\Game\Move\MoveRepositoryInterface;
use Src\Bc\Domain\Model\Game\Figures;
use Src\Bc\Domain\Model\Player\PlayerNotFoundException;
use Src\Bc\Domain\Model\Player\PlayerRepositoryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class Handler
{
    private PlayerRepositoryInterface $players;

    private GameRepositoryInterface $games;

    private MoveRepositoryInterface $moves;

    private FlusherInterface $flusher;

    private RulesDto $rules;

    private TranslatorInterface $translator;

    public function __construct(
        PlayerRepositoryInterface $players,
        GameRepositoryInterface $games,
        MoveRepositoryInterface $moves,
        FlusherInterface $flusher,
        RulesDto $rules,
        TranslatorInterface $translator
    ) {
        $this->players = $players;
        $this->games = $games;
        $this->moves = $moves;
        $this->flusher = $flusher;
        $this->rules = $rules;
        $this->translator = $translator;
    }

    /**
     * @param Command $command
     *
     * @return Result
     * @throws Exception
     * @throws RuntimeException
     */
    public function handle(Command $command): Result
    {
        try {
            $player = $this->players->getBySubscriberId($command->getSubscriberId());
            $game = $this->games->getNewByPlayerId($player->getId());
            $move = new Move(Id::next(), $game, new Figures($command->getFigures()));
        } catch (InvalidArgumentException|PlayerNotFoundException|GameNotFoundException $e) {
            throw new RuntimeException($this->translator->trans($e->getMessage()), $e->getCode(), $e);
        }

        if ($game->isMovesLimitReached($this->rules)) {
            $game->loose();
            $this->flusher->flush($move, $game);

            throw new RuntimeException($this->translator->trans('game.attempt_limit_reached'));
        }

        $result = $game->getFigures()->compare($move->getFigures());
        $game->finishMove($result->isVictory());

        $this->moves->add($move);
        $this->flusher->flush($move, $game);

        $result->setMovesLeft($game->getRemainingNumberOfMoves($this->rules));

        return $result;
    }
}
