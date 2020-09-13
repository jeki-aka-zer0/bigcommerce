<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\CartSession;

interface CartSessionRepository
{
    public function add(CartSession $cartSession): void;
}
