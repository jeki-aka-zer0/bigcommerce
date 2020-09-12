<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Src\Core\Application\Integration\Create\Handler as IntegrationCreateHandler;
use Src\Core\Application\Integration\View\Handler as IntegrationViewHandler;
use Src\Core\Application\Integration\Update\Handler as IntegrationUpdateHandler;
use Src\Core\Application\Integration\Uninstall\Handler as IntegrationUninstallHandler;
use Src\Core\Domain\Model\Auth\CredentialsDto;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Store\StoreRepository;
use Src\Core\Domain\Model\Webhook\Scopes;
use Src\Core\Domain\Model\Script\ScriptManager;
use Src\Core\Domain\Model\Webhook\WebhookManager;
use Src\Core\Domain\Model\LoadBodyExtractor;
use Src\Core\Infrastructure\Domain\Model\Auth\DoctrineIntegrationRepository;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;
use Src\Core\Infrastructure\Domain\Model\DoctrineFlusher;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Infrastructure\Domain\Model\DoctrineRemover;
use Src\Core\Infrastructure\Domain\Model\Store\DoctrineStoreRepository;

return [
    FlusherInterface::class => fn(ContainerInterface $c) => new DoctrineFlusher(
        $c->get(EntityManagerInterface::class),
    ),

    DoctrineRemover::class => fn(ContainerInterface $c) => new DoctrineRemover(
        $c->get(EntityManagerInterface::class),
    ),

    CredentialsDto::class => fn(ContainerInterface $c) => new CredentialsDto(
        $c->get('config')['credentials']['clientId'],
        $c->get('config')['credentials']['clientSecret'],
        $c->get('config')['main']['domain'] . $c->get('config')['credentials']['redirectPath'],
    ),

    IntegrationRepository::class => fn(ContainerInterface $c) => new DoctrineIntegrationRepository($c->get(EntityManagerInterface::class)),

    IntegrationCreateHandler::class => fn(ContainerInterface $c) => new IntegrationCreateHandler(
        $c->get(CredentialsDto::class),
        $c->get(IntegrationRepository::class),
        $c->get(StoreRepository::class),
        $c->get(FlusherInterface::class),
        $c->get(ClientConfigurator::class),
        $c->get(WebhookManager::class),
    ),

    IntegrationViewHandler::class => fn(ContainerInterface $c) => new IntegrationViewHandler(
        $c->get(LoadBodyExtractor::class),
        $c->get(IntegrationRepository::class),
    ),

    IntegrationUpdateHandler::class => fn(ContainerInterface $c) => new IntegrationUpdateHandler(
        $c->get(IntegrationRepository::class),
        $c->get(FlusherInterface::class),
        $c->get(WebhookManager::class),
        $c->get(ScriptManager::class),
    ),

    IntegrationUninstallHandler::class => fn(ContainerInterface $c) => new IntegrationUninstallHandler(
        $c->get(LoadBodyExtractor::class),
        $c->get(IntegrationRepository::class),
        $c->get(DoctrineRemover::class),
    ),

    WebhookManager::class => fn(ContainerInterface $c) => new WebhookManager(
        $c->get(ClientConfigurator::class),
        $c->get('config')['webhook']['scopes'],
        $c->get('config')['main']['domain'] . $c->get('config')['webhook']['receivePath'],
    ),

    ScriptManager::class => fn(ContainerInterface $c) => new ScriptManager(
        $c->get(ClientConfigurator::class),
        $c->get('config')['main']['domain'] . $c->get('config')['script']['jsPath'],
    ),

    StoreRepository::class => fn(ContainerInterface $c) => new DoctrineStoreRepository($c->get(EntityManagerInterface::class)),

    'config' => [
        'main' => [
            'domain' => 'https://env-6234666.jelastic.regruhosting.ru',
        ],
        'webhook' => [
            'receivePath' => '/big-commerce/webhook/receive',
            'scopes' => Scopes::ALL,
        ],
        'script' => [
            'jsPath' => '/bigcommerce.js',
        ],
        'credentials' => [
            'clientId' => '36j3cwu6kcwj5ne43oizbagywtq4o7f',
            'clientSecret' => 'a3b8f147e607c2f7d8929203e639e1ba5629982ebad59395b2b968e8584acbba',
            'redirectPath' => '/big-commerce/auth',
        ],
    ],
];
