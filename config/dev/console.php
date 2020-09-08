<?php

declare(strict_types=1);

use Doctrine\Migrations\Tools\Console\Command;

return [
    Command\DiffCommand::class => fn() => new Command\DiffCommand(),

    Command\MigrateCommand::class => fn() => new Command\MigrateCommand(),

    'config' => [
        'console' => [
            'commands' => [
                Command\DiffCommand::class,
                Command\MigrateCommand::class,
            ],
        ],
    ],
];
