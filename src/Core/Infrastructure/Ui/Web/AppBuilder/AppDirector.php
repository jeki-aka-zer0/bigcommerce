<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\AppBuilder;

use DI\Bridge\Slim\Bridge;
use Slim\App;
use Src\Core\Infrastructure\Ui\Shared\AppBuilder\ContainerBuilder;
use Src\Core\Infrastructure\Ui\Shared\AppBuilder\EnvLoader;

final class AppDirector
{
    public static function build(): App
    {
        (new EnvLoader())->load();

        $container = ContainerBuilder::build();
        $app = Bridge::create($container);

        (new MiddlewareBuilder($app))->build();
        (new HandlerBuilder($app))->build();
        (new RoutesBuilder($app))->build();

        return $app;
    }
}
