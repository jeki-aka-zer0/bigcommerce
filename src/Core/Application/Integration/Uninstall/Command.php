<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Uninstall;

final class Command
{
    private string $signedPayload;

    public function __construct(string $signedPayload)
    {
        $this->signedPayload = $signedPayload;
    }

    public function getSignedPayload(): string
    {
        return $this->signedPayload;
    }
}
