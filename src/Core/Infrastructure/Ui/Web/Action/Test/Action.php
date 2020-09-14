<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\Test;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Templating\PhpEngine;

final class Action implements RequestHandlerInterface
{
    private PhpEngine $phpEngine;

    public function __construct(PhpEngine $phpEngine)
    {
        $this->phpEngine = $phpEngine;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->phpEngine->render('Test/view.phtml'));
    }
}
