<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Link;

use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Application\Integration\Link\Handler;
use Src\Core\Application\Integration\Link\Command;

class Action implements RequestHandlerInterface
{
    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        $command = new Command($params['cart_id'], $params['session_id'], (int) $params['account_id'], $params['store_hash']);

        $this->handler->handle($command);

        return new EmptyResponse();
    }
}
