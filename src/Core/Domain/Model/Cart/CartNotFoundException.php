<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class CartNotFoundException extends CommonRuntimeException
{
    public function __construct(string $cartId, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Cart not found: %s', $cartId);

        parent::__construct($message, $code, $previous);
    }
}
