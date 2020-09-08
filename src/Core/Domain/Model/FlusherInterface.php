<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model;

interface FlusherInterface
{
    public function flush(object ...$root): void;
}
