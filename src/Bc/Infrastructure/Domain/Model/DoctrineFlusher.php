<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Domain\Model;

use Doctrine\ORM\EntityManagerInterface;
use Src\Bc\Domain\Model\FlusherInterface;

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
