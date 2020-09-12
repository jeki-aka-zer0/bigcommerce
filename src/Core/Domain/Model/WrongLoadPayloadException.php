<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model;

use Throwable;

final class WrongLoadPayloadException extends CommonRuntimeException
{
    public function __construct(string $message = 'Wrong load payload', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
