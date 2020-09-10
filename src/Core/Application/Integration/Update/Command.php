<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Update;

final class Command
{
    private string $apiKey;

    private string $storeHash;

    public function __construct(string $storeHash, string $apiKey)
    {
        $this->storeHash = $storeHash;
        $this->apiKey = $apiKey;
    }

    public function getStoreHash(): string
    {
        return $this->storeHash;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
