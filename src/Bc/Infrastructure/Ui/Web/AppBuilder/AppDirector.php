<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\AppBuilder;

use DI\Bridge\Slim\Bridge;
use Slim\App;
use Src\Bc\Infrastructure\Ui\Shared\AppBuilder\ContainerBuilder;
use Src\Bc\Infrastructure\Ui\Shared\AppBuilder\EnvLoader;

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
