<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Middleware;

use Src\Core\Domain\Model\CommonRuntimeException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ErrorsCatcherMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            return new ErrorsJsonResponse((string)$e->getErrors());
        } catch (CommonRuntimeException $e) {
            return new ErrorsJsonResponse($e->getMessage());
        }
    }
}
