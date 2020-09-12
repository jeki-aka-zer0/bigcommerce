<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Store;

use Bigcommerce\Api\Client;

final class StoreExtractor
{
    private $store;

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
