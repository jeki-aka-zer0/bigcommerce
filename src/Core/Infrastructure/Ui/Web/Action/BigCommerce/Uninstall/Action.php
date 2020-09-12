<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Uninstall;

use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Application\Integration\View\Handler;
use Src\Core\Application\Integration\View\Command;

class Action implements RequestHandlerInterface
{
    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $command = new Command($queryParams['signed_payload']);

        $this->handler->handle($command);

        return new EmptyResponse();
    }
}
