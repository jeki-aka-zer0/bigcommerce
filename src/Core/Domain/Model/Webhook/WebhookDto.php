<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Store\Store;

final class WebhookDto
{
    private string $scope;

    private Data $data;

    private string $hash;

    private Store $store;

    private Integration $integration;

    public function __construct(string $scope, Data $data, string $hash, Store $store, Integration $integration)
    {
        $this->scope = $scope;
        $this->data = $data;
        $this->store = $store;
        $this->integration = $integration;
        $this->hash = $hash;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getData(): Data
    {
        return $this->data;
    }

    public function getHash(): Hash
    {
        return new Hash($this->hash);
    }

    public function getStore(): Store
    {
        return $this->store;
    }

    public function getIntegration(): Integration
    {
        return $this->integration;
    }
}
