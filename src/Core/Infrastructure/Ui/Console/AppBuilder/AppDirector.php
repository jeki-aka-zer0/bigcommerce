<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Console\AppBuilder;

use Doctrine\DBAL\Tools\Console\ConsoleRunner as DBALRunnerAlias;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner as ORMConsoleRunner;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Src\Core\Infrastructure\Ui\Shared\AppBuilder\ContainerBuilder;
use Src\Core\Infrastructure\Ui\Shared\AppBuilder\EnvLoader;
use Symfony\Component\Console\Application;

final class AppDirector
{
    public static function build(): Application
    {
        (new EnvLoader())->load();

        $container = ContainerBuilder::build();
        $cli = new Application('Application console');

        $entityManager = $container->get(EntityManagerInterface::class);
        $connection = $entityManager->getConnection();

        $configuration = new Configuration($connection);
        $configuration->setMigrationsDirectory('src/Core/Infrastructure/Migration');
        $configuration->setMigrationsNamespace('Src\Core\Infrastructure\Migration');

        $cli->getHelperSet()->set(new EntityManagerHelper($entityManager), 'em');
        $cli->getHelperSet()->set(new ConfigurationHelper($connection, $configuration), 'configuration');
        $cli->getHelperSet()->set(new ConnectionHelper($connection), 'db');

        ORMConsoleRunner::addCommands($cli);
        DBALRunnerAlias::addCommands($cli);

        array_map(
            fn(string $class) => $cli->add($container->get($class)),
            $container->get('config')['console']['commands'] ?? []
        );

        return $cli;
    }
}
