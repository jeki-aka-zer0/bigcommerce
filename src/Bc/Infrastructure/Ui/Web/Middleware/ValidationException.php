<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\Middleware;

use Src\Bc\Infrastructure\Ui\Web\Validator\Errors;
use Src\Bc\Domain\Model\CommonRuntimeException;

final class ValidationException extends CommonRuntimeException
{
    private Errors $errors;

    public function __construct(Errors $errors, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct('Validation errors.', $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): Errors
    {
        return $this->errors;
    }
}
