<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class StoreAlreadyExistsException extends CommonRuntimeException
{
    public function __construct(string $message = 'Store already exists', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
