<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\Home;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Action implements RequestHandlerInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(
            [
                'name' => $this->name,
                'version' => '1.0',
            ]
        );
    }
}
