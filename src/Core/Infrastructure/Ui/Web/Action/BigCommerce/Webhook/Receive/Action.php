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
        $body = $request->getParsedBody();

        $log = new \Monolog\Logger('name');
        $log->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_DIR . '/var/log/wh.log'));
        $log->warning(serialize($body));

//        $command = new Command($body['score'], $body['data']);
//
//        $this->handler->handle($command);

        return new EmptyResponse();
    }
}
