<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model;

use Doctrine\ORM\EntityManagerInterface;
use Src\Core\Domain\Model\FlusherInterface;

final class DoctrineFlusher implements FlusherInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(object ...$root): void
    {
        $this->em->flush();
    }
}
