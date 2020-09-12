<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Create;

use Src\Core\Domain\Model\Auth\AuthTokenExtractor;
use Src\Core\Domain\Model\Auth\CredentialsDto;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Store\Store;
use Src\Core\Domain\Model\Store\StoreExtractor;
use Src\Core\Domain\Model\Store\StoreRepository;
use Src\Core\Domain\Model\Webhook\WebhookManager;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Id;

final class Handler
{
    private CredentialsDto $credentials;

    private IntegrationRepository $integrations;

    private StoreRepository $stores;

    private FlusherInterface $flusher;

    private WebhookManager $webhookManager;

    public function __construct(
        CredentialsDto $credentials,
        IntegrationRepository $integrations,
        StoreRepository $stores,
        FlusherInterface $flusher,
        WebhookManager $webhookManager
    ) {
        $this->credentials = $credentials;
        $this->integrations = $integrations;
        $this->stores = $stores;
        $this->flusher = $flusher;
        $this->webhookManager = $webhookManager;
    }

    public function handle(Command $command): void
    {
        $this->credentials->code = $command->getCode();
        $this->credentials->context = $command->getContext();
        $this->credentials->scope = $command->getScope();

        $authTokenExtractor = new AuthTokenExtractor($this->credentials);
        $storeHash = $authTokenExtractor->getHash();

        $integration = $this->integrations->findByStoreHash($storeHash);
        if (null !== $integration) {
            return;
        }

        $integration = new Integration(Id::next(), $storeHash, (array)$authTokenExtractor->getResponse());

        // @todo тут оно нам нужно? Мы еще не знаем pageId
//        $this->webhookManager->subscribe($integration);

        $storeExtractor = new StoreExtractor();
        $store = new Store($storeExtractor->getId(), $integration, (array)$storeExtractor->getStore());

        $this->integrations->add($integration);
        $this->stores->add($store);

        $this->flusher->flush($integration);
    }
}
