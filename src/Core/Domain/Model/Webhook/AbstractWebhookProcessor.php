<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

abstract class AbstractWebhookProcessor implements WebhookProcessor
{
    protected WebhookDto $dto;

    public function __construct(WebhookDto $dto)
    {
        $this->dto = $dto;
    }
}
