<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Shared\AppBuilder;

use DI\Container;
use DI\Definition\Source\DefinitionArray;
use Exception;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use Psr\Container\ContainerInterface;

final class ContainerBuilder
{
    /**
     * @return ContainerInterface
     * @throws Exception
     */
    public static function build(): ContainerInterface
    {
        $aggregator = new ConfigAggregator(
            [
                new PhpFileProvider(ROOT_DIR . '/config/common/*.php'),
                new PhpFileProvider(ROOT_DIR . '/config/' . (getenv('ENV') ?: 'prod') . '/*.php'),
            ]
        );

        $config = $aggregator->getMergedConfig();
        $definition = new DefinitionArray($config);

        return new Container($definition);
    }
}
