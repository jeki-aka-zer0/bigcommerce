<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use Webmozart\Assert\Assert;

final class Hash
{
    private string $hash;

    public function __construct(string $hash)
    {
        $this->hash = trim($hash);
        Assert::notEmpty($hash);
    }

    public function getHash(): string
    {
        return trim($this->hash);
    }

    public function __toString(): string
    {
        return trim($this->hash);
    }
}
