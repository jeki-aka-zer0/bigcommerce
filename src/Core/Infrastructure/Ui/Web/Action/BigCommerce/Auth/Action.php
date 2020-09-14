<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Auth;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Application\Integration\Create\Command;
use Src\Core\Application\Integration\Create\Handler;
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
        $queryParams = $request->getQueryParams();
        $command = new Command($queryParams['code'], $queryParams['context'], $queryParams['scope']);

        $this->handler->handle($command);

        $params = [
            'integration' => $this->handler->getIntegration(),
        ];

        return new HtmlResponse($this->phpEngine->render('BigCommerce/Load/view.phtml', $params));
    }
}
