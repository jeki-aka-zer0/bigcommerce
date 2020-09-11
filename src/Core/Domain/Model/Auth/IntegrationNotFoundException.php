<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class IntegrationNotFoundException extends CommonRuntimeException
{
    public function __construct(string $hash, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Integration not found: %s', $hash);

        parent::__construct($message, $code, $previous);
    }
}
