<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class WrongHashException extends CommonRuntimeException
{
    public function __construct(string $context, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Wrong hash: %s', $context);

        parent::__construct($message, $code, $previous);
    }
}
