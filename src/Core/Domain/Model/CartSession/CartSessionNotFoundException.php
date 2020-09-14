<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\CartSession;

use Src\Core\Domain\Model\CommonRuntimeException;

final class CartSessionNotFoundException extends CommonRuntimeException
{
    public static function sessionNotFoundByCartId(string $cartId): self
    {
        $message = sprintf('Session not found by cartId: %s', $cartId);

        return new self($message);
    }
}
