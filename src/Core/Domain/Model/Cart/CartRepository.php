<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

interface CartRepository
{
    public function findById(string $id): ?Cart;

    /**
     * @param string $id
     *
     * @return Cart
     * @throws CartNotFoundException
     */
    public function getById(string $id): Cart;

    public function add(Cart $cart): void;
}
