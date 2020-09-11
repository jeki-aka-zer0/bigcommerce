<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Update;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Application\Integration\Update\Handler;
use Src\Core\Application\Integration\Update\Command;

class Action implements RequestHandlerInterface
{
    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $command = new Command($body['store_hash'], $body['api_key']);

        $this->handler->handle($command);

        return new HtmlResponse('<h1>The application is installed and configured</h1>');
    }
}
