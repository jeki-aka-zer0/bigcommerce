<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\CartRedirect;

final class Command
{
    private string $cartId;

    public function __construct(string $cartId)
    {
        $this->cartId = $cartId;
    }

    public function getCartId(): string
    {
        return $this->cartId;
    }
}
