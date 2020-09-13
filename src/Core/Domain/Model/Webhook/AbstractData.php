<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Webmozart\Assert\Assert;

abstract class AbstractData implements Data
{
    private string $storeId;

    public function __construct(array $data)
    {
        $this->storeId = (string)($data[self::KEY_STORE_ID] ?? '');

        Assert::notEmpty($this->storeId);
    }

    public function getStoreId(): string
    {
        return $this->storeId;
    }
}
