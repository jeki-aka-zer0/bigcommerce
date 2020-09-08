<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\Action;

interface FormInterface
{
    public function toArray(): array;
}
