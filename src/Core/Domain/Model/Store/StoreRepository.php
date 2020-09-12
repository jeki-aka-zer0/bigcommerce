<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Store;

interface StoreRepository
{
    public function findById(string $id): ?Store;

    /**
     * @param string $id
     *
     * @return Store
     * @throws StoreNotFoundException
     */
    public function getById(string $id): Store;

    public function add(Store $store): void;
}
