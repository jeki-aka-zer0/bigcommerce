<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\AppBuilder;

use Slim\Interfaces\ErrorHandlerInterface;

final class HandlerBuilder extends AbstractBuilder
{
    public function build(): void
    {
        $config = $this->getContainer()->get('config')['logger'];
        $errorMiddleware = $this->getApp()->addErrorMiddleware(
            $config['displayErrorDetails'],
            $config['logErrors'],
            $config['logErrorDetails']
        );
        $handler = $this->getContainer()->get(ErrorHandlerInterface::class);
        $errorMiddleware->setDefaultErrorHandler($handler);
    }
}
