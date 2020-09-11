<?php

declare(strict_types=1);

namespace Src\Core\Application\Webhook\Receive;

final class Command
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
