<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

interface WebhookProcessor
{
    public function process(WebhookDto $dto): void;
}
