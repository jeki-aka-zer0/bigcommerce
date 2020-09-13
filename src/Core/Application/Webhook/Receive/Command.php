<?php

declare(strict_types=1);

namespace Src\Core\Application\Webhook\Receive;

final class Command
{
    private string $score;

    private string $storeId;

    private array $data;

    public function __construct(string $score, string $storeId, array $data)
    {
        $this->score = $score;
        $this->storeId = $storeId;
        $this->data = $data;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    public function getStoreId(): string
    {
        return $this->storeId;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
