<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\CartSession;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="carts_sessions")
 */
final class CartSession
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     */
    private string $cartId;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $sessionId;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private int $accountId;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $storeHash;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="create_at")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(string $cartId, string $sessionId, int $accountId, string $storeHash)
    {
        $this->cartId = $cartId;
        $this->sessionId = $sessionId;
        $this->accountId = $accountId;
        $this->storeHash = $storeHash;
        $this->createdAt = new DateTimeImmutable();
    }
}
