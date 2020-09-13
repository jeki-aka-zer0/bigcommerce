<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action\Test;

use Bigcommerce\Api\Client;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class Action implements RequestHandlerInterface
{
    private IntegrationRepository $integrationRepository;

    private ClientConfigurator $clientConfigurator;

    public function __construct(IntegrationRepository $integrationRepository, ClientConfigurator $clientConfigurator)
    {
        $this->integrationRepository = $integrationRepository;
        $this->clientConfigurator = $clientConfigurator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $cartId = '56f0eb3a-e6ed-46ae-9ce2-fe67a7393226';
        $storeHash = 'li15qvqduw';

        $integration = $this->integrationRepository->findByStoreHash(new Hash($storeHash));

        $this->clientConfigurator->configureV3($integration);

        $cart = Client::getResource('/carts/' . $cartId);

        echo '<pre>';
        var_dump($cart);

        return new HtmlResponse('');
    }
}
