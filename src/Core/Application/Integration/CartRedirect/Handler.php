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
    private CartRepository $cartSessionRepository;

    private IntegrationRepository $integrationRepository;

    private ClientConfigurator $clientConfigurator;

    private ?string $checkoutUrl = null;

    public function __construct(
        ClientConfigurator $clientConfigurator,
        CartSessionRepository $cartSessionRepository,
        IntegrationRepository $integrationRepository
    ) {
        $this->clientConfigurator = $clientConfigurator;
        $this->cartSessionRepository = $cartSessionRepository;
        $this->integrationRepository = $integrationRepository;
    }

    public function handle(Command $command): void
    {
        $cartSession = $this->cartSessionRepository->getByCartId($command->getCartId());
        $integration = $this->integrationRepository->getByStoreHash(new Hash($cartSession->getStoreHash()));

        $this->clientConfigurator->configureV3($integration);
        $redirectUrls = Client::createResource('/carts/' . $command->getCartId() . '/redirect_urls', []);

        if (!$redirectUrls) {
            throw new WrongLoadPayloadException();
        }

        $this->checkoutUrl = $redirectUrls['data']['checkout_url'];
    }

    public function getCheckoutUrl(): string
    {
        return $this->checkoutUrl;
    }
}
