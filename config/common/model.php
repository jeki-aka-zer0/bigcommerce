<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Src\Bc\Infrastructure\Domain\Model\Game\DoctrineGameRepository;
use Src\Bc\Infrastructure\Domain\Model\Game\Move\DoctrineMoveRepository;
use Src\Bc\Infrastructure\Domain\Model\Player\DoctrinePlayerRepository;
use Src\Bc\Infrastructure\Domain\Model\DoctrineFlusher;
use Src\Bc\Infrastructure\Domain\Model\Game\Score\DoctrineScoreRepository;
use Src\Bc\Domain\Model\FlusherInterface;
use Src\Bc\Domain\Model\Game\GameRepositoryInterface;
use Src\Bc\Domain\Model\Game\RulesDto;
use Src\Bc\Domain\Model\Game\Move\MoveRepositoryInterface;
use Src\Bc\Domain\Model\Game\Score\ScoreBoard;
use Src\Bc\Domain\Model\Game\Score\ScoreRepositoryInterface;
use Src\Bc\Domain\Model\Player\PlayerRepositoryInterface;
use Src\Bc\Application\Player\Register\Handler as PlayerHandler;
use Src\Bc\Application\Game\Start\Handler as GameHandler;
use Src\Bc\Application\Game\Move\Handler as MoveHandler;
use Src\Bc\Application\Game\Score\Handler as ScoreHandler;
use Src\Bc\Application\Game\Stop\Handler as StopHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

return [
    FlusherInterface::class => fn(ContainerInterface $c) => new DoctrineFlusher(
        $c->get(EntityManagerInterface::class),
    ),

    PlayerRepositoryInterface::class => fn(ContainerInterface $c) => new DoctrinePlayerRepository(
        $c->get(EntityManagerInterface::class),
    ),

    PlayerHandler::class => fn(ContainerInterface $c) => new PlayerHandler(
        $c->get(PlayerRepositoryInterface::class),
        $c->get(FlusherInterface::class),
    ),

    GameRepositoryInterface::class => fn(ContainerInterface $c) => new DoctrineGameRepository(
        $c->get(EntityManagerInterface::class),
    ),

    GameHandler::class => fn(ContainerInterface $c) => new GameHandler(
        $c->get(PlayerRepositoryInterface::class),
        $c->get(GameRepositoryInterface::class),
        $c->get(FlusherInterface::class),
        $c->get(TranslatorInterface::class),
    ),

    MoveRepositoryInterface::class => fn(ContainerInterface $c) => new DoctrineMoveRepository(
        $c->get(EntityManagerInterface::class),
    ),

    RulesDto::class => fn(ContainerInterface $container) => new RulesDto(
        $container->get('config')['game']['rules']['max_moves_count_for_hard_level'],
        $container->get('config')['game']['rules']['points_for_hard_victory'],
        $container->get('config')['game']['rules']['points_for_easy_victory'],
        $container->get('config')['game']['rules']['points_for_losing'],
        $container->get('config')['game']['rules']['score_board_size'],
    ),

    MoveHandler::class => fn(ContainerInterface $c) => new MoveHandler(
        $c->get(PlayerRepositoryInterface::class),
        $c->get(GameRepositoryInterface::class),
        $c->get(MoveRepositoryInterface::class),
        $c->get(FlusherInterface::class),
        $c->get(RulesDto::class),
        $c->get(TranslatorInterface::class),
    ),

    ScoreRepositoryInterface::class => fn(ContainerInterface $c) => new DoctrineScoreRepository(
        $c->get(EntityManagerInterface::class),
        $c->get(RulesDto::class),
    ),

    ScoreHandler::class => fn(ContainerInterface $c) => new ScoreHandler(
        $c->get(ScoreRepositoryInterface::class),
        $c->get(ScoreBoard::class),
    ),

    StopHandler::class => fn(ContainerInterface $c) => new StopHandler(
        $c->get(PlayerRepositoryInterface::class),
        $c->get(GameRepositoryInterface::class),
        $c->get(FlusherInterface::class),
        $c->get(TranslatorInterface::class),
    ),

    ScoreBoard::class => fn(ContainerInterface $c) => new ScoreBoard(
        $c->get(RulesDto::class),
    ),

    'config' => [
        'game' => [
            'rules' => [
                'max_moves_count_for_hard_level' => 10,
                'points_for_hard_victory' => 3,
                'points_for_easy_victory' => 2,
                'points_for_losing' => 1,
                'score_board_size' => 10,
            ],
        ],
    ],
];
