<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class WrongResponseException extends CommonRuntimeException
{
    public function __construct(string $message = 'Wrong response', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
