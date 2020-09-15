<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\CartRedirect;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Application\Integration\CartRedirect\Command;
use Src\Core\Application\Integration\CartRedirect\Handler;

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
        $command = new Command($queryParams['cart_id'], (bool) ($queryParams['debug'] ?? false));

        $this->handler->handle($command);

        return new RedirectResponse($this->handler->getCheckoutUrl());
    }
}
