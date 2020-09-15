<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Cart\CartRepository;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;
use Src\Core\Domain\Model\Job\JobProcessor;
use Src\Core\Domain\Model\Job\JobRepository;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;
use Src\Core\Infrastructure\Ui\Console\Command\JobsCommand;

return [
    JobsCommand::class => function (ContainerInterface $c) {
        $config = $c->get('config')['logger'];
        $logger = new Logger($config['name']);
        $logger->pushHandler(new StreamHandler($config['file']));

        return new JobsCommand(
            $c->get(JobRepository::class),
            new JobProcessor(
                $c->get(CartSessionRepository::class),
                $c->get(CartRepository::class),
                $c->get(IntegrationRepository::class),
                $c->get(ClientConfigurator::class)
            ),
            $logger,
        );
    },

    'config' => [
        'console' => [
            'commands' => [
                JobsCommand::class,
            ],
        ],
        'logger' => [
            'name' => 'JOB',
            'file' => ROOT_DIR . '/var/log/jobs.log',
            'displayErrorDetails' => false,
            'logErrors' => true,
            'logErrorDetails' => true,
        ],
    ],
];
