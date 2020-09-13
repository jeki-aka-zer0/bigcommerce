<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use Src\Core\Domain\Model\Webhook\AbstractData;
use Webmozart\Assert\Assert;

final class CartData extends AbstractData
{
    public const KEY_CART_ID = 'id';

    public const TYPE_CART = 'cart';

    private string $cartId;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->cartId = (string)($data[self::KEY_CART_ID] ?? '');

        Assert::notEmpty($this->cartId);
        Assert::eq($this->type, self::TYPE_CART);
    }

    public function getCartId(): string
    {
        return $this->cartId;
    }
}
