<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\Test;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Script\ScriptManager;

final class Action implements RequestHandlerInterface
{
    private IntegrationRepository $integrationRepository;

    private ScriptManager $scriptManager;

    public function __construct(IntegrationRepository $integrationRepository, ScriptManager $scriptManager)
    {
        $this->integrationRepository = $integrationRepository;
        $this->scriptManager = $scriptManager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $storeHash = $request->getQueryParams()['store_hash'];
        $integration = $this->integrationRepository->getByStoreHash(new Hash($storeHash));

        $this->scriptManager->addToStore($integration);

        return new HtmlResponse('Ok');
    }
}
