<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Job;

use GuzzleHttp\Client;
use Src\Core\Domain\Model\Cart\CartRepository;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;
use Src\Core\Domain\Model\CommonRuntimeException;
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
        if ($cart->isPaid()) {
            throw new CommonRuntimeException('Cart is paid'); // @todo fix
        }

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

        $cartPayload = $cart->getPayload();
        $lineItems = array_merge($cartPayload['line_items']['physical_items'], $cartPayload['line_items']['digital_items'], $cartPayload['line_items']['custom_items']);

        if (empty($lineItems)) {
            throw new CommonRuntimeException('Cart is empty'); // @todo fix
        }

        $mostExpensiveLineItem = $lineItems[0];
        foreach ($lineItems as $lineItem) {
            if ($lineItem['list_price'] > $mostExpensiveLineItem['list_price']) {
                $mostExpensiveLineItem = $lineItem;
            }
        }

        $options = [
            'body' => json_encode([
                'version' => 1,
                'subscriber_id' => $subscriberId,
                'trigger_name' => 'abandoned_cart',
                'context' => [
                    'cart_url' => $this->domain . '/big-commerce/cart-redirect?cart_id=' . $cartId,
                    'cart_price' => (float) $cartPayload['cart_amount'],

                    'most_expensive_product_image' => $mostExpensiveLineItem['image_url'],
                    'most_expensive_product_title' => $mostExpensiveLineItem['name'],
                    'most_expensive_product_price' => $mostExpensiveLineItem['list_price'],
                    'most_expensive_product_quantity' => $mostExpensiveLineItem['quantity'],
                ],
            ]),
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $integration->getTriggerApiKey()),
                'Content-Type' => 'application/json',
            ]
        ];

        (new Client())->post('https://manychat.com/apps/wh', $options);
    }
}
