<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Action;

interface FormInterface
{
    public function toArray(): array;
}
