<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Webhook\WebhookDto;
use Src\Core\Domain\Model\Webhook\WebhookProcessor;

final class CartWebhookProcessor implements WebhookProcessor
{
    private CartRepository $carts;

    private FlusherInterface $flusher;

    public function __construct(CartRepository $carts, FlusherInterface $flusher)
    {
        $this->carts = $carts;
        $this->flusher = $flusher;
    }

    public function process(WebhookDto $dto): void
    {
        /** @var CartData $data */
        $data = $dto->getData();
        $cartRaw = []; // @todo get cart by API
        $cart = $this->carts->findById($data->getCartId());

        if (null === $cart) {
            $cart = new Cart($data->getCartId(), $cartRaw);
            $this->carts->add($cart);
        } else {
            $cart->updatePayload($cartRaw);
        }

        $this->flusher->flush($cart);
    }
}
