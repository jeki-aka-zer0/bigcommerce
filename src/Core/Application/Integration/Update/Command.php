<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Update;

final class Command
{
    private string $triggerApiKey;

    private string $storeHash;

    public function __construct(string $storeHash, string $triggerApiKey)
    {
        $this->storeHash = $storeHash;
        $this->triggerApiKey = $triggerApiKey;
    }

    public function getStoreHash(): string
    {
        return $this->storeHash;
    }

    public function getTriggerApiKey(): string
    {
        return $this->triggerApiKey;
    }
}
