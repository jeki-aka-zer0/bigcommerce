<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Job\JobRepository;
use Src\Core\Domain\Model\Job\Sign;
use Src\Core\Domain\Model\Webhook\WebhookDto;
use Src\Core\Domain\Model\Webhook\WebhookProcessor;

final class CartConvertedWebhookProcessor implements WebhookProcessor
{
    private FlusherInterface $flusher;

    private JobRepository $jobs;

    private CartRepository $carts;

    public function __construct(FlusherInterface $flusher, JobRepository $jobs, CartRepository $carts)
    {
        $this->flusher = $flusher;
        $this->jobs = $jobs;
        $this->carts = $carts;
    }

    public function process(WebhookDto $dto): void
    {
        /** @var CartData $data */
        $data = $dto->getData();

        $cart = $this->carts->findById($data->getCartId());
        if (null !== $cart) {
            $cart->markAsPaid();
        }

        $sign = Sign::build(CartWebhookProcessor::TRIGGER_KEY_CART, $data->getCartId());
        $this->jobs->removeAllBySign($sign);

        $this->flusher->flush();
    }
}
