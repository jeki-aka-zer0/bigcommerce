<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

interface Data
{
    public const KEY_STORE_ID = 'store_id';
    public const KEY_TYPE     = 'type';

    public function getStoreId(): string;
}
