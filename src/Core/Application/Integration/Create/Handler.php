<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Create;

use Src\Core\Domain\Model\Auth\AuthTokenExtractor;
use Src\Core\Domain\Model\Auth\CredentialsDto;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Store\StoreExtractor;
use Src\Core\Domain\Model\Store\StoreRepository;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Id;

final class Handler
{
    private CredentialsDto $credentials;

    private IntegrationRepository $integrations;

    private StoreRepository $stores;

    private FlusherInterface $flusher;

    private StoreExtractor $storeExtractor;

    public function __construct(
        CredentialsDto $credentials,
        IntegrationRepository $integrations,
        StoreRepository $stores,
        FlusherInterface $flusher,
        StoreExtractor $storeExtractor
    ) {
        $this->credentials = $credentials;
        $this->integrations = $integrations;
        $this->stores = $stores;
        $this->flusher = $flusher;
        $this->storeExtractor = $storeExtractor;
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

        $store = $this->storeExtractor->extract($integration);

        $this->integrations->add($integration);
        $this->stores->add($store);

        $this->flusher->flush($integration);
    }
}
