<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Cart\CartRepository;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;
use Src\Core\Domain\Model\Job\JobProcessor;
use Src\Core\Domain\Model\Job\JobRepository;
use Src\Core\Infrastructure\Ui\Console\Command\JobsCommand;

return [
    JobsCommand::class => fn(ContainerInterface $c) => new JobsCommand(
        $c->get(JobRepository::class),
        new JobProcessor(
            $c->get(CartSessionRepository::class),
            $c->get(CartRepository::class),
            $c->get(IntegrationRepository::class),
        ),
    ),

    'config' => [
        'console' => [
            'commands' => [
                JobsCommand::class,
            ],
        ],
    ],
];
