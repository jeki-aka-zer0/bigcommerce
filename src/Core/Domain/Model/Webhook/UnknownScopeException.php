<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class UnknownScopeException extends CommonRuntimeException
{
    public function __construct(string $scope, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Unknown webhook: %s', $scope);

        parent::__construct($message, $code, $previous);
    }
}
