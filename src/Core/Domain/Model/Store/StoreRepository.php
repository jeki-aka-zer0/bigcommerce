<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Store;

interface StoreRepository
{
    public function findById(int $id): ?Store;

    /**
     * @param int $id
     *
     * @return Store
     * @throws StoreNotFoundException
     */
    public function getById(int $id): Store;

    public function add(Store $store): void;
}
