<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Store;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Src\Core\Domain\Model\Auth\Integration;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity
 * @ORM\Table(name="stores", uniqueConstraints={
 *     @UniqueConstraint(name="store_id_uidx", columns={"id"})
 * })
 */
class Store
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    private int $id;

    /**
     * @var Integration
     * @ORM\ManyToOne(targetEntity="Src\Core\Domain\Model\Auth\Integration")
     * @ORM\JoinColumn(name="integration_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Integration $integration;

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

    public function __construct(int $id, Integration $integration, array $payload)
    {
        $this->id = $id;
        $this->integration = $integration;
        $this->payload = $payload;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getIntegration(): Integration
    {
        return $this->integration;
    }
}
