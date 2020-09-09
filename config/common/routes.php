<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Src\Core\Application\Integration\Create\Handler as IntegrationCreateHandler;
use Src\Core\Infrastructure\Ui\Web\Action;

return [
    Action\Home\Action::class => fn() => new Action\Home\Action(getenv('APP_NAME')),

    Action\BigCommerce\Auth\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Auth\Action(
        $c->get(IntegrationCreateHandler::class),
    ),
];
