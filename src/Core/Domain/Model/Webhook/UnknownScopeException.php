<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Src\Core\Domain\Model\CommonRuntimeException;

final class UnknownScopeException extends CommonRuntimeException
{
    public static function unknownWebhook(string $scope): self
    {
        $message = sprintf('Unknown webhook: %s', $scope);

        return new self($message);
    }
}
