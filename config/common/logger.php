<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    LoggerInterface::class => function (ContainerInterface $container): LoggerInterface {
        $config = $container->get('config')['logger'];
        $logger = new Logger($config['name']);
        $logger->pushHandler(new StreamHandler($config['file']));

        return $logger;
    },

    'config' => [
        'logger' => [
            'name' => 'API',
            'file' => ROOT_DIR . '/var/log/app.log',
            'displayErrorDetails' => false,
            'logErrors' => true,
            'logErrorDetails' => true,
        ],
    ],
];