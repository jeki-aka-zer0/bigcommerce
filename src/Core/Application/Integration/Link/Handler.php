<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Link;

use Src\Core\Domain\Model\CartSession\CartSession;
use Src\Core\Domain\Model\CartSession\CartSessionRepository;
use Src\Core\Domain\Model\FlusherInterface;

final class Handler
{
    private CartSessionRepository $cartSessionRepository;

    private FlusherInterface $flusher;

    public function __construct(CartSessionRepository $cartSessionRepository, FlusherInterface $flusher)
    {
        $this->cartSessionRepository = $cartSessionRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $cartSession = new CartSession($command->getCartId(), $command->getSessionId(), $command->getAccountId(), $command->getStoreHash());

        $this->cartSessionRepository->add($cartSession);
        $this->flusher->flush($cartSession);
    }
}
