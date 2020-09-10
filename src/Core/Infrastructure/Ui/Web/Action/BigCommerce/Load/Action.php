<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\BigCommerce\Load;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Application\Integration\View\Handler;
use Src\Core\Application\Integration\View\Command;

class Action implements RequestHandlerInterface
{
    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $command = new Command($queryParams['signed_payload']);

        $integration = $this->handler->handle($command);

        if (empty($integration->getApiKey())) {
            return new HtmlResponse('Load Success (no api key)');
        }

        return new HtmlResponse('Load Success (api key)');
    }
}
