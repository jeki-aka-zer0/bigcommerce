<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Webmozart\Assert\Assert;

abstract class AbstractData implements Data
{
    protected string $type;

    public function __construct(array $data)
    {
        $this->type = (string)($data[self::KEY_TYPE] ?? '');

        Assert::notEmpty($this->type);
    }
}
