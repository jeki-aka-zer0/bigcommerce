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
class Integration
{
    /**
     * @var Id
     * @ORM\Column(type="id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var Hash
     * @ORM\Column(type="hash")
     */
    private Hash $storeHash;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $apiKey;

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

    public function __construct(Id $id, Hash $storeHash, array $authPayload)
    {
        $this->id = $id;
        $this->storeHash = $storeHash;
        $this->authPayload = $authPayload;
        $this->createdAt = new DateTimeImmutable();
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function getAccountId(): ?int
    {
        return (int) explode(':', $this->getApiKey())[0];
    }

    public function getAccessToken(): string
    {
        return $this->authPayload['access_token'];
    }

    public function getStoreHash(): Hash
    {
        return $this->storeHash;
    }
}
