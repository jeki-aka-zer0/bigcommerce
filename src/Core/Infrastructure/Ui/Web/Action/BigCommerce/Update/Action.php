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
        $command = new Command(
            $body['store_hash'],
            $body['trigger_api_key'],
            $body['public_api_key'],
            (int) $body['abandoned_period'],
            $body['abandoned_unit']
        );

        $this->handler->handle($command);

        $params = [
            'integration' => $this->handler->getIntegration(),
        ];

        return new HtmlResponse($this->phpEngine->render('BigCommerce/Load/view.phtml', $params));
    }
}
