<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model\CartSession;

use Doctrine\ORM\EntityManagerInterface;
use Src\Core\Domain\Model\CartSession\CartSession;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;

final class DoctrineCartSessionRepository implements CartSessionRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(CartSession $cartSession): void
    {
        $this->em->persist($cartSession);
    }
}
