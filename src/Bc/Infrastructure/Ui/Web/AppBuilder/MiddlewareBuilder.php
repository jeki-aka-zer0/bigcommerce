<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\AppBuilder;

use Src\Bc\Infrastructure\Ui\Web\Middleware\ErrorsCatcherMiddleware;
use Src\Bc\Infrastructure\Ui\Web\Middleware\JsonBodyParserMiddleware;

final class MiddlewareBuilder extends AbstractBuilder
{
    public function build(): void
    {
        $this->getApp()->add(ErrorsCatcherMiddleware::class . '::process');
        $this->getApp()->add(JsonBodyParserMiddleware::class . '::process');
    }
}
