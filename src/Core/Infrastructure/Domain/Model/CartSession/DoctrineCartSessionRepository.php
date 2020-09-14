<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model\CartSession;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Src\Core\Domain\Model\CartSession\CartSession;
use Src\Core\Domain\Model\CartSession\CartSessionNotFoundException;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;

final class DoctrineCartSessionRepository implements CartSessionRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(CartSession::class);
    }

    public function add(CartSession $cartSession): void
    {
        $this->em->persist($cartSession);
    }

    public function findByCartId(string $cartId): ?CartSession
    {
        /** @var CartSession|null $cartSession */
        $cartSession = $this->repo->findOneBy(['cartId' => $cartId]);

        return $cartSession;
    }

    public function getByCartId(string $cartId): CartSession
    {
        $cartSession = $this->findByCartId($cartId);

        if (null === $cartSession) {
            throw CartSessionNotFoundException::sessionNotFoundByCartId($cartId);
        }

        return $cartSession;
    }
}
