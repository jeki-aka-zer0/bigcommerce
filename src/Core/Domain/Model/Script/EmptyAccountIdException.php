<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Script;

use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class EmptyAccountIdException extends CommonRuntimeException
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('Empty account id', $code, $previous);
    }
}
