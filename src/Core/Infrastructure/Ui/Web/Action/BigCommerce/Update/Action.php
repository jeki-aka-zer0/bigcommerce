<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Update;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Application\Integration\Update\Handler;
use Src\Core\Application\Integration\Update\Command;
use Symfony\Component\Templating\PhpEngine;

class Action implements RequestHandlerInterface
{
    private Handler $handler;

    private PhpEngine $phpEngine;

    public function __construct(Handler $handler, PhpEngine $phpEngine)
    {
        $this->handler = $handler;
        $this->phpEngine = $phpEngine;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $command = new Command($body['store_hash'], $body['api_key']);

        $this->handler->handle($command);

        return new HtmlResponse($this->phpEngine->render('BigCommerce/Update/view.phtml'));
    }
}
