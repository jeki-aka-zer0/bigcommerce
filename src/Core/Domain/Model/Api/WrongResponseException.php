<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Api;

use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class WrongResponseException extends CommonRuntimeException
{
    public function __construct(string $scope, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Wrong response: %s', $scope);

        parent::__construct($message, $code, $previous);
    }
}
