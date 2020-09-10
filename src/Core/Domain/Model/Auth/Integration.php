<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use DateTimeImmutable;
use Src\Core\Domain\Model\Id;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="integrations", indexes={@ORM\Index(name="store_hash_uidx", columns={"storeHash"})})
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
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $storeHash;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    private array $authPayload;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="create_at")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(Id $id, string $storeHash, array $authPayload)
    {
        $this->id = $id;
        $this->storeHash = $storeHash;
        $this->authPayload = $authPayload;
        $this->createdAt = new DateTimeImmutable();
    }
}
