<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\Action\Game\Level\Choose;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Bc\Application\Game\Start\Handler;
use Src\Bc\Application\Game\Start\Command;
use Src\Bc\Application\RuntimeException;

final class Action implements RequestHandlerInterface
{
    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws Exception
     * @throws RuntimeException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = new Command($request->getAttribute('subscriberId'), $request->getAttribute('level'));

        $this->handler->handle($command);

        return new JsonResponse([]);
    }
}
