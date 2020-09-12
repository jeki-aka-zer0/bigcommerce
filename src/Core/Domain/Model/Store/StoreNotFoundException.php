<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Store;

use Src\Core\Domain\Model\CommonRuntimeException;
use Throwable;

final class StoreNotFoundException extends CommonRuntimeException
{
    public function __construct(string $storeId, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Store not found: %s', $storeId);

        parent::__construct($message, $code, $previous);
    }
}
