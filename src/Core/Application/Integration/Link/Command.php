<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Link;

final class Command
{
    private string $cartId;

    private string $sessionId;

    private int $accountId;

    private string $storeHash;

    public function __construct(string $cartId, string $sessionId, int $accountId, string $storeHash)
    {
        $this->cartId = $cartId;
        $this->sessionId = $sessionId;
        $this->accountId = $accountId;
        $this->storeHash = $storeHash;
    }

    public function getCartId(): string
    {
        return $this->cartId;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getStoreHash(): string
    {
        return $this->storeHash;
    }
}
