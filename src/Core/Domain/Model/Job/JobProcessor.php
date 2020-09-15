<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Job;

use GuzzleHttp\Client;
use Src\Core\Domain\Model\Cart\CartRepository;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;
use Src\Core\Domain\Model\WrongLoadPayloadException;

final class JobProcessor
{
    private CartSessionRepository $cartSessions;

    private CartRepository $carts;

    private string $domain;

    public function __construct(
        CartSessionRepository $cartSessions,
        CartRepository $carts,
        string $domain
    ) {
        $this->cartSessions = $cartSessions;
        $this->carts = $carts;
        $this->domain = $domain;
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
            var_dump($response);
            throw new WrongLoadPayloadException('Not success status'); // @todo fix
        }

        $subscriberId = $response['data']['subscriber_id'] ?? null;
        if (empty($subscriberId)) {
            var_dump($cartSession->getSessionId());
            var_dump($response);
            throw new WrongLoadPayloadException('No subscriber_id'); // @todo fix
        }

//        $this->clientConfigurator->configureV3($integration);
//        $redirectUrls = BigcommerceClient::createResource('/carts/' . $cartId . '/redirect_urls', []);
//
//        if (!$redirectUrls) {
//            var_dump(BigcommerceClient::getLastError());
//            throw new WrongLoadPayloadException(); // @todo fix
//        }
//        $redirectUrls['data']['checkout_url']

        // Дока - https://support.manychat.com/support/solutions/articles/36000228026-dev-program-quick-start#How-to-Use-Triggers
        $options = [
            'body' => json_encode([
                'version' => 1,
                'subscriber_id' => $subscriberId,
                'trigger_name' => 'abandoned_cart', // @todo config
                'context' => [  // @todo
//                    Для начала хватит этих двух
                    'cart_url' => $this->domain . '/big-commerce/cart-redirect?cart_id=' . $cartId
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
