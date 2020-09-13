<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use Src\Core\Domain\Model\Webhook\Data;

abstract class LineItemDataAdapter
{
    private const KEY_CART_ID = 'cartId';

    private const TYPE_CART = 'cart_line_item';

    public static function getCartData(array $data): CartData
    {
        return new CartData(
            [
                Data::KEY_TYPE => CartData::TYPE_CART,
                CartData::KEY_CART_ID => $data[self::KEY_CART_ID],
            ] + $data
        );
    }
}
