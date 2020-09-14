<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Update;

final class Command
{
    protected string $publicApiKey;
    protected int $abandonedPeriod;
    protected string $abandonedUnit;
    private string $triggerApiKey;

    private string $storeHash;

    public function __construct(
        string $storeHash,
        string $triggerApiKey,
        string $publicApiKey,
        int $abandonedPeriod,
        string $abandonedUnit
    ) {
        $this->storeHash = $storeHash;
        $this->triggerApiKey = $triggerApiKey;
        $this->publicApiKey = $publicApiKey;
        $this->abandonedPeriod = $abandonedPeriod;
        $this->abandonedUnit = $abandonedUnit;
    }

    public function getStoreHash(): string
    {
        return $this->storeHash;
    }

    public function getTriggerApiKey(): string
    {
        return $this->triggerApiKey;
    }

    public function getPublicApiKey(): string
    {
        return $this->publicApiKey;
    }

    public function getAbandonedPeriod(): int
    {
        return $this->abandonedPeriod;
    }

    public function getAbandonedUnit(): string
    {
        return $this->abandonedUnit;
    }
}
