<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Job;

use GuzzleHttp\Client;
use Bigcommerce\Api\Client as BigcommerceClient;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Cart\CartRepository;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;
use Src\Core\Domain\Model\WrongLoadPayloadException;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class JobProcessor
{
    private CartSessionRepository $cartSessions;

    private CartRepository $carts;

    private IntegrationRepository $integrations;

    private ClientConfigurator $clientConfigurator;

    public function __construct(CartSessionRepository $cartSessions, CartRepository $carts, IntegrationRepository $integrations, ClientConfigurator $clientConfigurator)
    {
        $this->cartSessions = $cartSessions;
        $this->carts = $carts;
        $this->integrations = $integrations;
        $this->clientConfigurator = $clientConfigurator;
    }

    public function process(Job $job): void
    {
        $cartId = $job->getSign()->getIdentity();
        $cartSession = $this->cartSessions->getByCartId($cartId);
        $cart = $this->carts->getById($cartId);
        $integration = $job->getIntegration();

        $url = sprintf('https://manychat.com/apiPixel/getSession?session_id=%s', $cartSession->getSessionId());
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $integration->getPublicApiKey()),
            ]
        ];
        $response = (new Client())->get($url, $options);
        $response = json_decode((string)$response->getBody(), true); // @todo need?

        if ('success' !== ($response['status'] ?? '')) {
            throw new WrongLoadPayloadException(); // @todo fix
        }

        $subscriberId = $response['data']['subscriber_id'] ?? null;
        if (empty($subscriberId)) {
            throw new WrongLoadPayloadException(); // @todo fix
        }

        $this->clientConfigurator->configureV3($integration);
        $redirectUrls = BigcommerceClient::createResource('/carts/' . $cartId . '/redirect_urls', []);

        if (!$redirectUrls) {
            var_dump(BigcommerceClient::getLastError());
            throw new WrongLoadPayloadException(); // @todo fix
        }

        // Дока - https://support.manychat.com/support/solutions/articles/36000228026-dev-program-quick-start#How-to-Use-Triggers
        $options = [
            'body' => json_encode([
                'version' => 1,
                'subscriber_id' => $subscriberId,
                'trigger_name' => 'abandoned_cart', // @todo config
                'context' => [  // @todo
//                    Для начала хватит этих двух
                    'cart_url' => $redirectUrls['data']['checkout_url']
//                    Cart Price

//                    First Added Product Image
//                    First Added Product Title
//                    First Added Product Price
//                    First Added Product Quantity
//                    Most Expensive Product Image
//                    Most Expensive Product Title
//                    Most Expensive Product Price
//                    Most Expensive Product Quantity
//                    Cart Is Paid (no)
                ],
            ]),
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $integration->getTriggerApiKey()),
                'Content-Type' => 'application/json',
            ]
        ];
        $response = (new Client())->post('https://manychat.com/apps/wh', $options);
        $response = json_decode((string)$response->getBody(), true); // @todo need?

        var_dump($response);

        // @todo check response
    }
}
