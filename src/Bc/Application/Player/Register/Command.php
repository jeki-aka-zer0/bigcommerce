<?php

declare(strict_types=1);

namespace Src\Bc\Application\Player\Register;

final class Command
{
    private int $subscriberId;

    private string $name;

    public function __construct(int $subscriberId, string $name)
    {
        $this->subscriberId = $subscriberId;
        $this->name = $name;
    }

    public function getSubscriberId(): int
    {
        return $this->subscriberId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
