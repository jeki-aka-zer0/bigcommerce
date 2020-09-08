<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model;

use Src\Core\Domain\Model\FlusherInterface;

final class DummyFlusher implements FlusherInterface
{
    public function flush(object ...$root): void
    {
    }
}
