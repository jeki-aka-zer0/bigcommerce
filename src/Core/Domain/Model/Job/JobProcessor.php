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

        $url = sprintf('https://dev.manychat.com/apiPixel/getSession?session_id=%s', $cartSession->getSessionId());
        $options = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $integration->getPublicApiKey()),
            ]
        ];
        $response = (new Client())->get($url, $options);
        $response = json_decode((string)$response->getBody()); // @todo need?

        if ($response['status'] ?? '' !== 'success') {
            throw new WrongLoadPayloadException(); // @todo fix
        }

        $subscriberId = $response['data']['subscriber_id'] ?? null;
        if (empty($subscriberId)) {
            throw new WrongLoadPayloadException(); // @todo fix
        }

        // @todo call trigger
    }
}
