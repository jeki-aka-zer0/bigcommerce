<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use Src\Core\Domain\Model\Webhook\WebhookDto;
use Src\Core\Domain\Model\Webhook\WebhookProcessor;

final class CartWebhookProcessor implements WebhookProcessor
{
    private const KEY_CART_ID = 'id';

    private CartRepository $carts;

    public function __construct(CartRepository $carts)
    {
        $this->carts = $carts;
    }

    public function process(WebhookDto $dto): void
    {
        $cartId = $dto->getData()[self::KEY_CART_ID];
        $cart = $this->carts->findById($cartId);
        // @todo get cart by API

        if (null === $cart) {
            $this->carts->add($cart);
        } else {
            $cart->updatePayload();
        }
    }
}
