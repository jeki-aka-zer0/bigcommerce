<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Load;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Application\Integration\View\Handler;
use Src\Core\Application\Integration\View\Command;
use Symfony\Component\Templating\PhpEngine;

class Action implements RequestHandlerInterface
{
    private PhpEngine $phpEngine;

    private Handler $handler;

    public function __construct(Handler $handler, PhpEngine $phpEngine)
    {
        $this->handler = $handler;
        $this->phpEngine = $phpEngine;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $command = new Command($queryParams['signed_payload']);

        $this->handler->handle($command);

        $params = [
            'integration' => $this->handler->getIntegration(),
        ];

        return new HtmlResponse($this->phpEngine->render('BigCommerce/Load/view.phtml', $params));
    }
}
