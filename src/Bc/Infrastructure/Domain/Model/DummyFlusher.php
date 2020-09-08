<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Domain\Model;

use Src\Bc\Domain\Model\FlusherInterface;

final class DummyFlusher implements FlusherInterface
{
    public function flush(object ...$root): void
    {
    }
}
