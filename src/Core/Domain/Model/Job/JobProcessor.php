<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Job;

use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Cart\CartRepository;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;

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
    }
}
