<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\AppBuilder;

use Src\Core\Infrastructure\Ui\Web\Middleware\ErrorsCatcherMiddleware;
use Src\Core\Infrastructure\Ui\Web\Middleware\JsonBodyParserMiddleware;

final class MiddlewareBuilder extends AbstractBuilder
{
    public function build(): void
    {
        $this->getApp()->add(ErrorsCatcherMiddleware::class . '::process');
        $this->getApp()->add(JsonBodyParserMiddleware::class . '::process');
    }
}
