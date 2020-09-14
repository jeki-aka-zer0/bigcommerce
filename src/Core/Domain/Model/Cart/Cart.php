<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="carts", indexes={@ORM\Index(name="cart_id_uidx", columns={"id"})})
 */
final class Cart
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
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

    private const KEY_CART_ID = 'id';

    public function __construct(string $id, array $payload)
    {
        $this->id = $id;
        $this->updatePayload($payload);
        $this->createdAt = new DateTimeImmutable();
    }

    public function updatePayload(array $payload): void
    {
        $idFromPayload = $payload[self::KEY_CART_ID] ?? '';
        if ($idFromPayload !== $this->id) {
            throw InvalidPayload::differentIds($this->id, $idFromPayload);
        }

        $this->payload = $payload;
    }
}
