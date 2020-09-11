<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Webhook\Receive;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Application\Webhook\Receive\Command;
use Src\Core\Application\Webhook\Receive\Handler;
use Laminas\Diactoros\Response\EmptyResponse;

final class Action implements RequestHandlerInterface
{
    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = new Command($request->getAttribute('data'));

        $this->handler->handle($command);

        return new EmptyResponse();
    }
}
