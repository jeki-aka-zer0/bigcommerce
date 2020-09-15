<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\CartRedirect;

final class Command
{
    private string $cartId;

    private bool $debug;

    public function __construct(string $cartId, bool $debug = false)
    {
        $this->cartId = $cartId;
        $this->debug = $debug;
    }

    public function getCartId(): string
    {
        return $this->cartId;
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }
}
