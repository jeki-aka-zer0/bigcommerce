<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Src\Bc\Application\Player\Register\Handler as PlayerHandler;
use Src\Bc\Application\Game\Start\Handler as GameHandler;
use Src\Bc\Application\Game\Move\Handler as MoveHandler;
use Src\Bc\Application\Game\Score\Handler as ScoreHandler;
use Src\Bc\Application\Game\Stop\Handler as StopHandler;
use Src\Bc\Infrastructure\Ui\Web\Action;

return [
    Action\Home\Action::class => fn() => new Action\Home\Action(getenv('APP_NAME')),

    Action\Init\Action::class => fn(ContainerInterface $c) => new Action\Init\Action(
        $c->get(PlayerHandler::class),
    ),

    Action\Game\Level\Choose\Action::class => fn(ContainerInterface $c) => new Action\Game\Level\Choose\Action(
        $c->get(GameHandler::class),
    ),

    Action\Game\Move\Action::class => fn(ContainerInterface $c) => new Action\Game\Move\Action(
        $c->get(MoveHandler::class),
    ),

    Action\Game\Stop\Action::class => fn(ContainerInterface $c) => new Action\Game\Stop\Action(
        $c->get(StopHandler::class),
    ),

    Action\Game\Score\Action::class => fn(ContainerInterface $c) => new Action\Game\Score\Action(
        $c->get(ScoreHandler::class),
    ),
];
