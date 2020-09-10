<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Src\Core\Application\Integration\Create\Handler as IntegrationCreateHandler;
use Src\Core\Application\Integration\View\Handler as IntegrationViewHandler;
use Src\Core\Domain\Model\Auth\CredentialsDto;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Infrastructure\Domain\Model\Auth\DoctrineIntegrationRepository;
use Src\Core\Infrastructure\Domain\Model\DoctrineFlusher;
use Src\Core\Domain\Model\FlusherInterface;

return [
    FlusherInterface::class => fn(ContainerInterface $c) => new DoctrineFlusher(
        $c->get(EntityManagerInterface::class),
    ),

    CredentialsDto::class => fn(ContainerInterface $c) => new CredentialsDto(
        $c->get('config')['credentials']['clientId'],
        $c->get('config')['credentials']['clientSecret'],
        $c->get('config')['credentials']['redirect_uri'],
    ),

    IntegrationRepository::class => fn(ContainerInterface $c) => new DoctrineIntegrationRepository(
        $c->get(EntityManagerInterface::class),
    ),

    IntegrationCreateHandler::class => fn(ContainerInterface $c) => new IntegrationCreateHandler(
        $c->get(CredentialsDto::class),
        $c->get(IntegrationRepository::class),
        $c->get(FlusherInterface::class),
    ),

    IntegrationViewHandler::class => fn(ContainerInterface $c) => new IntegrationViewHandler(),

    'config' => [
        'credentials' => [
            'clientId' => '36j3cwu6kcwj5ne43oizbagywtq4o7f',
            'clientSecret' => 'a3b8f147e607c2f7d8929203e639e1ba5629982ebad59395b2b968e8584acbba',
            'redirect_uri' => 'https://env-6234666.jelastic.regruhosting.ru/big-commerce/auth',
        ],
    ],
];
