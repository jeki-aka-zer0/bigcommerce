<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model\Cart;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Src\Core\Domain\Model\Cart\Cart;
use Src\Core\Domain\Model\Cart\CartNotFoundException;
use Src\Core\Domain\Model\Cart\CartRepository;

final class DoctrineCartRepository implements CartRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Cart::class);
    }

    public function findById(string $id): ?Cart
    {
        /** @var Cart $cart */
        $cart = $this->repo->findOneBy(['id' => $id]);

        return $cart;
    }

    public function getById(string $id): Cart
    {
        $cart = $this->findById($id);

        if (null === $cart) {
            throw new CartNotFoundException($id);
        }

        return $cart;
    }

    public function add(Cart $cart): void
    {
        $this->em->persist($cart);
    }
}
