<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\CartRedirect;

use Bigcommerce\Api\Client;
use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Cart\CartRepository;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;
use Src\Core\Domain\Model\WrongLoadPayloadException;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class Handler
{
    private ClientConfigurator $clientConfigurator;

    private IntegrationRepository $integrationRepository;

    private CartSessionRepository $cartSessionRepository;

    private CartRepository $cartRepository;

    private ?string $checkoutUrl = null;

    public function __construct(
        ClientConfigurator $clientConfigurator,
        IntegrationRepository $integrationRepository,
        CartSessionRepository $cartSessionRepository,
        CartRepository $cartRepository
    ) {
        $this->clientConfigurator = $clientConfigurator;
        $this->integrationRepository = $integrationRepository;
        $this->cartSessionRepository = $cartSessionRepository;
        $this->cartRepository = $cartRepository;
    }

    public function handle(Command $command): void
    {
        $cart = $this->cartRepository->getById($command->getCartId());
        $cartSession = $this->cartSessionRepository->getByCartId($command->getCartId());
        $integration = $this->integrationRepository->getByStoreHash(new Hash($cartSession->getStoreHash()));

        $this->clientConfigurator->configureV3($integration);
        $response = Client::createResource('/carts?include=redirect_urls', [
            'line_items' => array_merge(
                $cart->getPayload()['line_items']['digital_items'],
                $cart->getPayload()['line_items']['physical_items'],
            ),
            'custom_items' => $cart->getPayload()['line_items']['custom_items'],
        ]);

        if (!$response) {
            // @todo process
            echo '<pre>';
            var_dump(Client::getLastError());
            throw new WrongLoadPayloadException();
        }

        if ($command->isDebug()) {
            echo '<pre>';
            var_dump($response);
            exit;
        }

        $this->checkoutUrl = $response->data->redirect_urls->checkout_url;
    }

    public function getCheckoutUrl(): string
    {
        return $this->checkoutUrl;
    }
}
