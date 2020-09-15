<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Src\Core\Domain\Model\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity
 * @ORM\Table(name="integrations", uniqueConstraints={
 *     @UniqueConstraint(name="store_hash_uidx", columns={"storeHash"})
 * })
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
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $triggerApiKey = null;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $publicApiKey = null;

    /**
     * @var string
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private ?int $abandonedPeriod = null;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $abandonedUnit = null;

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
        $this->apiKey = $storeHash . ':' . Uuid::uuid4()->toString();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setTriggerApiKey(string $triggerApiKey): void
    {
        $this->triggerApiKey = $triggerApiKey;
    }

    public function setPublicApiKey(string $publicApiKey): void
    {
        $this->publicApiKey = $publicApiKey;
    }

    public function setAbandonedPeriod(int $abandonedPeriod): void
    {
        $this->abandonedPeriod = $abandonedPeriod;
    }

    public function setAbandonedUnit(string $abandonedUnit): void
    {
        $this->abandonedUnit = $abandonedUnit;
    }

    public function getTriggerApiKey(): ?string
    {
        return $this->triggerApiKey;
    }

    public function setAuthPayload(array $authPayload): void
    {
        $this->authPayload = $authPayload;
    }

    public function getAccountId(): ?int
    {
        return (int) explode(':', $this->getTriggerApiKey())[0];
    }

    public function getAccessToken(): string
    {
        return $this->authPayload['access_token'];
    }

    public function getStoreHash(): Hash
    {
        return $this->storeHash;
    }

    public function getPublicApiKey(): string
    {
        return $this->publicApiKey;
    }

    public function getAbandonedPeriod(): int
    {
        return $this->abandonedPeriod;
    }

    public function getAbandonedUnit(): string
    {
        return $this->abandonedUnit;
    }
}
