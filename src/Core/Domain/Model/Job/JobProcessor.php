<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Job;

use GuzzleHttp\Client;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Cart\CartRepository;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;
use Src\Core\Domain\Model\WrongLoadPayloadException;

final class JobProcessor
{
    private CartSessionRepository $cartSessions;

    private CartRepository $carts;

    private IntegrationRepository $integrations;

    public function __construct(CartSessionRepository $cartSessions, CartRepository $carts, IntegrationRepository $integrations)
    {
        $this->cartSessions = $cartSessions;
        $this->carts = $carts;
        $this->integrations = $integrations;
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

        if ($response['status'] ?? '' !== 'success') {
            var_dump($response);
            throw new WrongLoadPayloadException(); // @todo fix
        }

        $subscriberId = $response['data']['subscriber_id'] ?? null;
        if (empty($subscriberId)) {
            throw new WrongLoadPayloadException(); // @todo fix
        }

        // Дока - https://support.manychat.com/support/solutions/articles/36000228026-dev-program-quick-start#How-to-Use-Triggers
        $options = [
            'body' => [
                'version' => 1,
                'subscriber_id' => $subscriberId,
                'trigger_name' => 'abandoned_cart', // @todo config
//                'context' => [  // @todo
//                    Для начала хватит этих двух
//                    Cart Url
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
//                ],
            ],
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $integration->getTriggerApiKey()),
                'Accept' => 'application/json',
            ]
        ];
        $response = (new Client())->post('https://manychat.com/apps/wh', $options);
        $response = json_decode((string)$response->getBody(), true); // @todo need?

        var_dump($response);

        // @todo check response
    }
}
