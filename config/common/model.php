<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Src\Core\Application\Integration\Create\Handler as IntegrationCreateHandler;
use Src\Core\Application\Integration\View\Handler as IntegrationViewHandler;
use Src\Core\Application\Integration\Update\Handler as IntegrationUpdateHandler;
use Src\Core\Application\Integration\Uninstall\Handler as IntegrationUninstallHandler;
use Src\Core\Application\Integration\Link\Handler as IntegrationLinkHandler;
use Src\Core\Application\Integration\CartRedirect\Handler as IntegrationCartRedirectHandler;
use Src\Core\Application\Webhook\Receive\Handler as WebhookReceiveHandler;
use Src\Core\Domain\Model\Auth\CredentialsDto;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Cart\CartRepository;
use Src\Core\Domain\Model\Cart\CartWebhookProcessor;
use Src\Core\Domain\Model\Job\JobRepository;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;
use Src\Core\Domain\Model\Store\StoreExtractor;
use Src\Core\Domain\Model\Store\StoreRepository;
use Src\Core\Domain\Model\Webhook\Scopes;
use Src\Core\Domain\Model\Script\ScriptManager;
use Src\Core\Domain\Model\LoadBodyExtractor;
use Src\Core\Domain\Model\Webhook\WebhookFactory;
use Src\Core\Domain\Model\Webhook\WebhookManager;
use Src\Core\Infrastructure\Domain\Model\Auth\DoctrineIntegrationRepository;
use Src\Core\Infrastructure\Domain\Model\Cart\DoctrineCartRepository;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;
use Src\Core\Infrastructure\Domain\Model\DoctrineFlusher;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Infrastructure\Domain\Model\DoctrineRemover;
use Src\Core\Infrastructure\Domain\Model\CartSession\DoctrineCartSessionRepository;
use Src\Core\Infrastructure\Domain\Model\Job\DoctrineJobRepository;
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
        $c->get(StoreExtractor::class),
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
        $c->get(FlusherInterface::class),
    ),

    IntegrationLinkHandler::class => fn(ContainerInterface $c) => new IntegrationLinkHandler(
        $c->get(CartSessionRepository::class),
        $c->get(FlusherInterface::class),
    ),

    IntegrationCartRedirectHandler::class => fn(ContainerInterface $c) => new IntegrationCartRedirectHandler(
        $c->get(ClientConfigurator::class),
        $c->get(CartSessionRepository::class),
        $c->get(IntegrationRepository::class),
    ),

    WebhookReceiveHandler::class => fn(ContainerInterface $c) => new WebhookReceiveHandler(
        $c->get(StoreRepository::class),
        $c->get(IntegrationRepository::class),
        new WebhookFactory(new CartWebhookProcessor(
            $c->get(IntegrationRepository::class),
            $c->get(CartRepository::class),
            $c->get(FlusherInterface::class),
            $c->get(ClientConfigurator::class),
            $c->get(JobRepository::class),
        )),
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

    StoreExtractor::class => fn(ContainerInterface $c) => new StoreExtractor(
        $c->get(ClientConfigurator::class),
    ),

    StoreRepository::class => fn(ContainerInterface $c) => new DoctrineStoreRepository($c->get(EntityManagerInterface::class)),

    CartRepository::class => fn(ContainerInterface $c) => new DoctrineCartRepository($c->get(EntityManagerInterface::class)),

    CartSessionRepository::class => fn(ContainerInterface $c) => new DoctrineCartSessionRepository($c->get(EntityManagerInterface::class)),

    JobRepository::class => fn(ContainerInterface $c) => new DoctrineJobRepository($c->get(EntityManagerInterface::class)),

    'config' => [
        'main' => [
            'domain' => 'https://bigcommerce-manychat.site',
        ],
        'webhook' => [
            'receivePath' => '/big-commerce/webhook/receive',
            'scopes' => Scopes::ALL,
        ],
        'script' => [
            'jsPath' => '/bigcommerce.js?store_hash=%s&account_id=%s',
        ],
        'credentials' => [
            'clientId' => '40qcuzt2ol1afdtolicrck6mew5wqaq',
            'clientSecret' => '1ee17b0501eb3821be4d6667f44170cf43209391350f9f8142f0516f776894cd',
            'redirectPath' => '/big-commerce/auth',
        ],
    ],
];
