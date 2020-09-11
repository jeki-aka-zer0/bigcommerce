<?php

declare(strict_types=1);

namespace Src\Core\Application\Webhook\Receive;

final class Command
{
    private string $score;

    private array $data;

    public function __construct(string $score, array $data)
    {
        $this->score = $score;
        $this->data = $data;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
