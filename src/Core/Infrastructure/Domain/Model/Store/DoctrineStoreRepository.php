<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model\Store;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Src\Core\Domain\Model\Store\Store;
use Src\Core\Domain\Model\Store\StoreNotFoundException;
use Src\Core\Domain\Model\Store\StoreRepository;

final class DoctrineStoreRepository implements StoreRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Store::class);
    }

    public function findById(int $id): ?Store
    {
        /** @var Store $store */
        $store = $this->repo->findOneBy(['id' => $id]);

        return $store;
    }

    public function getById(int $id): Store
    {
        $store = $this->findById($id);

        if (null === $store) {
            throw new StoreNotFoundException($id);
        }

        return $store;
    }

    public function add(Store $store): void
    {
        $this->em->persist($store);
    }
}
