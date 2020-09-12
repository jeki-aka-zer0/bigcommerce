<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Store\StoreRepository;

final class WebhookHandler
{
    private const KEY_STORE_ID = 'store_id';

    private StoreRepository $stores;

    private IntegrationRepository $integrations;

    private WebhookProcessorFactory $processorFactory;

    public function __construct(StoreRepository $stores, IntegrationRepository $integrations, WebhookProcessorFactory $processor)
    {
        $this->stores = $stores;
        $this->integrations = $integrations;
        $this->processorFactory = $processor;
    }

    public function handle(string $scope, array $data): void
    {
        $store = $this->stores->getById($data[self::KEY_STORE_ID]);
        $integration = $this->integrations->getByStoreHash($store->getIntegration()->getStoreHash());

        $dto = new WebhookDto($scope, $data, $store, $integration);

        $this->processorFactory->build($scope)->process($dto);
    }
}
