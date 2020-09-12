<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model;

use Doctrine\ORM\EntityManagerInterface;

final class DoctrineRemover
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function remove(object $entity): void
    {
        $this->em->remove($entity);
    }
}
