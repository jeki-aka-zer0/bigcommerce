<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Player;

use DateTimeImmutable;
use Src\Bc\Domain\Model\Id;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="players", indexes={
 *     @ORM\Index(name="players_subscriber_id_idx", columns={"subscriber_id"}),
 * })
 */
final class Player
{
    /**
     * @var Id
     * @ORM\Column(type="id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var int
     * @ORM\Column(type="bigint", name="subscriber_id", unique=true)
     */
    private int $subscriberId;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="create_at")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(Id $id, int $subscriberId, string $name)
    {
        $this->id = $id;
        $this->subscriberId = $subscriberId;
        $this->name = $name;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getSubscriberId(): int
    {
        return $this->subscriberId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
