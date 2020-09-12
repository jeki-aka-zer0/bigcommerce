<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Store;

use DateTimeImmutable;

/**
 * @ORM\Entity
 * @ORM\Table(name="stores", indexes={@ORM\Index(name="store_id_uidx", columns={"id"})})
 */
final class Store
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $id;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private array $payload;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="create_at")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(string $id, array $payload)
    {
        $this->id = $id;
        $this->payload = $payload;
        $this->createdAt = new DateTimeImmutable();
    }
}
