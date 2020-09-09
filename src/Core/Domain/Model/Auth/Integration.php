<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use DateTimeImmutable;
use Src\Core\Domain\Model\Id;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="games")
 */
final class Integration
{
    /**
     * @var Id
     * @ORM\Column(type="id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="create_at")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(Id $id)
    {
        $this->id = $id;
        $this->createdAt = new DateTimeImmutable();
    }
}
