<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Src\Core\Application\Integration\Create\Handler as IntegrationCreateHandler;
use Src\Core\Application\Integration\View\Handler as IntegrationViewHandler;
use Src\Core\Application\Integration\Update\Handler as IntegrationUpdateHandler;
use Src\Core\Application\Integration\Uninstall\Handler as IntegrationUninstallHandler;
use Src\Core\Application\Integration\Link\Handler as IntegrationLinkHandler;
use Src\Core\Application\Integration\CartRedirect\Handler as IntegrationCartRedirectHandler;
use Src\Core\Application\Webhook\Receive\Handler as WebhookReceiveHandler;
use Src\Core\Domain\Model\Cart\CartRepository;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;
use Src\Core\Domain\Model\Job\JobProcessor;
use Src\Core\Domain\Model\Job\JobRepository;
use Src\Core\Infrastructure\Ui\Console\Command\JobsCommand;
use Src\Core\Infrastructure\Ui\Web\Action;
use Symfony\Component\Templating\PhpEngine;

return [
    Action\Home\Action::class => fn() => new Action\Home\Action(getenv('APP_NAME')),

    Action\Test\Action::class => fn(ContainerInterface $c) => new Action\Test\Action(
        $c->get(PhpEngine::class),
    ),

    Action\BigCommerce\Auth\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Auth\Action(
        $c->get(IntegrationCreateHandler::class),
        $c->get(PhpEngine::class),
    ),

    Action\BigCommerce\Update\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Update\Action(
        $c->get(IntegrationUpdateHandler::class),
        $c->get(PhpEngine::class),
    ),

    Action\BigCommerce\Load\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Load\Action(
        $c->get(IntegrationViewHandler::class),
        $c->get(PhpEngine::class),
    ),

    Action\BigCommerce\Uninstall\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Uninstall\Action(
        $c->get(IntegrationUninstallHandler::class),
    ),

    Action\BigCommerce\Webhook\Receive\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Webhook\Receive\Action(
        $c->get(WebhookReceiveHandler::class),
    ),

    Action\BigCommerce\Job\Action::class => function (ContainerInterface $c) {
        $config = $c->get('config')['logger'];
        $logger = new Logger($config['name']);
        $logger->pushHandler(new StreamHandler($config['file']));

        return new Action\BigCommerce\Job\Action(
            $c->get(JobRepository::class),
            new JobProcessor(
                $c->get(CartSessionRepository::class),
                $c->get(CartRepository::class),
                $c->get('config')['main']['domain']
            ),
            $logger,
        );
    },

    Action\BigCommerce\Link\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\Link\Action(
        $c->get(IntegrationLinkHandler::class),
    ),

    Action\BigCommerce\CartRedirect\Action::class => fn(ContainerInterface $c) => new Action\BigCommerce\CartRedirect\Action(
        $c->get(IntegrationCartRedirectHandler::class),
    ),
];
