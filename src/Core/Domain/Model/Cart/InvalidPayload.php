<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Cart;

use Src\Core\Domain\Model\CommonRuntimeException;

final class InvalidPayload extends CommonRuntimeException
{
    public static function differentIds(string $id1, string $id2): self
    {
        $message = sprintf('Try to update cart %s with payload of cart %s', $id1, $id2);

        return new self($message);
    }
}
