<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Src\Core\Application\Integration\Create\Handler as IntegrationCreateHandler;
use Src\Core\Application\Integration\View\Handler as IntegrationViewHandler;
use Src\Core\Application\Integration\Update\Handler as IntegrationUpdateHandler;
use Src\Core\Application\Integration\Uninstall\Handler as IntegrationUninstallHandler;
use Src\Core\Infrastructure\Ui\Web\Action;
use Symfony\Component\Templating\PhpEngine;

return [
    Action\Home\Action::class => fn() => new Action\Home\Action(getenv('APP_NAME')),

    Action\BigCommerce\Auth\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Auth\Action(
        $c->get(IntegrationCreateHandler::class),
    ),

    Action\BigCommerce\Update\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Update\Action(
        $c->get(IntegrationUpdateHandler::class),
    ),

    Action\BigCommerce\Load\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Load\Action(
        $c->get(IntegrationViewHandler::class),
        $c->get(PhpEngine::class),
    ),

    Action\BigCommerce\Uninstall\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Uninstall\Action(
        $c->get(IntegrationUninstallHandler::class),
    ),
];
