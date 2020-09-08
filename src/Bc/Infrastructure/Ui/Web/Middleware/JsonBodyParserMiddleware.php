<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class JsonBodyParserMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if (false !== strpos($contentType, 'application/json')) {
            $contents = json_decode(file_get_contents('php://input'), true);
            if (JSON_ERROR_NONE === json_last_error()) {
                $request = $request->withParsedBody($contents);
            }
        }

        return $handler->handle($request);
    }
}
