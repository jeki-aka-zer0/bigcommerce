<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Store;

use Bigcommerce\Api\Client;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class StoreExtractor
{
    private $store;

    private ClientConfigurator $clientConfigurator;

    public function __construct(ClientConfigurator $clientConfigurator)
    {
        $this->clientConfigurator = $clientConfigurator;
    }

    public function extract($integration): Store
    {
        $this->clientConfigurator->configureV2($integration);

        return new Store($this->getId(), $integration, (array)$this->getStore());
    }

    public function getStore()
    {
        if (null === $this->store) {
            $this->store = Client::getStore();
        }

        return $this->store;
    }

    public function getId(): string
    {
        return (string)$this->getStore()->id;
    }
}
