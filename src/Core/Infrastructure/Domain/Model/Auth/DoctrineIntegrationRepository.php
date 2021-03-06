<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model\Auth;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Auth\IntegrationNotFoundException;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Id;

final class DoctrineIntegrationRepository implements IntegrationRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Integration::class);
    }

    public function findById(Id $integrationId): ?Integration
    {
        /** @var Integration $integration */
        $integration = $this->repo->findOneBy(['id' => $integrationId->getId()]);

        return $integration;
    }

    public function findByStoreHash(Hash $storeHash): ?Integration
    {
        /** @var Integration $integration */
        $integration = $this->repo->findOneBy(['storeHash' => $storeHash->getHash()]);

        return $integration;
    }

    public function getByStoreHash(Hash $storeHash): Integration
    {
        $integration = $this->findByStoreHash($storeHash);

        if (null === $integration) {
            throw new IntegrationNotFoundException($storeHash->getHash());
        }

        return $integration;
    }

    public function add(Integration $integration): void
    {
        $this->em->persist($integration);
    }
}
