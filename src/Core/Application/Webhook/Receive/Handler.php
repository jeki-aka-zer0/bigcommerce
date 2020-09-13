<?php

declare(strict_types=1);

namespace Src\Core\Application\Webhook\Receive;

use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Store\StoreRepository;
use Src\Core\Domain\Model\Webhook\WebhookDto;
use Src\Core\Domain\Model\Webhook\WebhookFactory;

final class Handler
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

    public function handle(Command $command): void
    {
        $data = $this->processorFactory->getData($command->getScore(), $command->getData());
        $store = $this->stores->getById($data->getStoreId());
        $integration = $this->integrations->getByStoreHash($store->getIntegration()->getStoreHash());
        $dto = new WebhookDto($command->getScore(), $data, $store, $integration);

        $this->processorFactory->getProcessor($command->getScore())->process($dto);
    }
}
