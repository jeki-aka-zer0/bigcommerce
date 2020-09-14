<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Job;

use Src\Core\Domain\Model\CartSession\CartSessionRepository;

final class JobProcessor
{
    private CartSessionRepository $cartSessions;

    public function __construct(CartSessionRepository $cartSessions)
    {
        $this->cartSessions = $cartSessions;
    }

    public function process(Job $job): void
    {
        $cartId = $job->getSign()->getIdentity();
        $cartSession = $this->cartSessions->getByCartId($cartId);

    }
}
