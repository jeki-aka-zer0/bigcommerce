<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Src\Core\Infrastructure\Domain\Model\DoctrineFlusher;
use Src\Core\Domain\Model\FlusherInterface;

return [
    FlusherInterface::class => fn(ContainerInterface $c) => new DoctrineFlusher(
        $c->get(EntityManagerInterface::class),
    ),
];
