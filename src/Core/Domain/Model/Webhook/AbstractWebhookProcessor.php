<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

abstract class AbstractWebhookProcessor implements WebhookProcessor
{
    protected string $scope;

    protected array $data;

    public function __construct(string $scope, array $data)
    {
        $this->scope = $scope;
        $this->data = $data;
    }
}
