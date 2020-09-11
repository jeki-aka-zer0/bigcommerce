<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use Src\Core\Domain\Model\Id;

interface IntegrationRepository
{
    public function findById(Id $integrationId): ?Integration;

    public function findByStoreHash(Hash $storeHash): ?Integration;

    /**
     * @param Hash $storeHash
     *
     * @return Integration
     * @throws IntegrationNotFoundException
     */
    public function getByStoreHash(Hash $storeHash): Integration;

    public function add(Integration $game): void;
}
