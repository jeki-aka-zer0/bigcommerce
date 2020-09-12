<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Store\Store;

final class WebhookDto
{
    private string $scope;

    private array $data;

    private Store $store;

    private Integration $integration;

    public function __construct(string $scope, array $data, Store $store, Integration $integration)
    {
        $this->scope = $scope;
        $this->data = $data;
        $this->store = $store;
        $this->integration = $integration;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getData(): array
    {
        return $this->data;
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
