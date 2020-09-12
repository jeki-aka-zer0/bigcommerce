<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Cart\CartCreatedWebhookProcessor;
use Src\Core\Domain\Model\Store\StoreRepository;

final class WebhookProcessorFactory
{
    private const KEY_STORE_ID = 'store_id';

    private StoreRepository $stores;

    private IntegrationRepository $integrations;

    public function __construct(StoreRepository $stores, IntegrationRepository $integrations)
    {
        $this->stores = $stores;
        $this->integrations = $integrations;
    }

    public function build(string $scope, array $data): WebhookProcessor
    {
        $store = $this->stores->getById($data[self::KEY_STORE_ID]);
        $integration = $this->integrations->getByStoreHash($store->getIntegration()->getStoreHash());

        $dto = new WebhookDto($scope, $data, $store, $integration);

        switch ($scope) {
            case Scopes::CART_CREATED:
                return new CartCreatedWebhookProcessor($dto);
            case Scopes::CART_UPDATED:
                return new CartCreatedWebhookProcessor($dto);
        }

        throw new UnknownScopeException($scope);
    }
}
