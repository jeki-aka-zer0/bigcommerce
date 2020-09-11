<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

final class Scopes
{
    public const ALL = [
        self::CART_CREATED,
        self::CART_UPDATED,
    ];

    public const CART_CREATED = 'store/cart/created';
    public const CART_UPDATED = 'store/cart/updated';
}
