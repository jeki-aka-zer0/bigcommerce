<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

final class Scopes
{
    public const ALL = [
        self::CART_CREATED,
        self::CART_UPDATED,
        self::CART_CONVERTED,

        self::CART_LINE_ITEM_CREATED,
        self::CART_LINE_ITEM_UPDATED,
        self::CART_LINE_ITEM_DELETED,
    ];

    public const CART_CREATED   = 'store/cart/created';
    public const CART_UPDATED   = 'store/cart/updated';
    public const CART_CONVERTED = 'store/cart/converted';

    public const CART_LINE_ITEM_CREATED = 'store/cart/lineItem/created';
    public const CART_LINE_ITEM_UPDATED = 'store/cart/lineItem/updated';
    public const CART_LINE_ITEM_DELETED = 'store/cart/lineItem/deleted';
}
