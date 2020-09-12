<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Script;

use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class CreateScriptException extends CommonRuntimeException
{
    public function __construct(string $scope, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Create scripts error: %s', $scope);

        parent::__construct($message, $code, $previous);
    }
}
