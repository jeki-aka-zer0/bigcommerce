<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Middleware;

use Src\Core\Infrastructure\Ui\Web\Validator\Errors;
use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class ValidationException extends CommonRuntimeException
{
    private Errors $errors;

    public function __construct(string $message = '', int $code = 0, Throwable $previous = null, Errors $errors)
    {
        parent::__construct($message ?: 'Validation errors', $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): Errors
    {
        return $this->errors;
    }
}
