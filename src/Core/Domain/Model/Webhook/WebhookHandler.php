<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Store\StoreRepository;

final class WebhookHandler
{
    private StoreRepository $stores;

    private IntegrationRepository $integrations;

    private WebhookFactory $processorFactory;

    public function __construct(StoreRepository $stores, IntegrationRepository $integrations, WebhookFactory $processor)
    {
        $this->stores = $stores;
        $this->integrations = $integrations;
        $this->processorFactory = $processor;
    }

    public function handle(string $scope, array $dataRaw): void
    {
        $data = $this->processorFactory->getData($scope, $dataRaw);
        $store = $this->stores->getById($data->getStoreId());
        $integration = $this->integrations->getByStoreHash($store->getIntegration()->getStoreHash());
        $dto = new WebhookDto($scope, $data, $store, $integration);

        $this->processorFactory->getProcessor($scope)->process($dto);
    }
}
