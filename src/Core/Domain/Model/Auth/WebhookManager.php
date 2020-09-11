<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use Bigcommerce\Api\Client;

final class WebhookManager
{
    private array $scopes;

    private string $destination;

    public function __construct(array $scopes, string $destination)
    {
        $this->scopes = $scopes;
        $this->destination = $destination;
    }

    public function subscribe(): void
    {
        array_map(
            fn(string $scope) => Client::createWebhook(
                [
                    'scope' => $scope,
                    'destination' => $this->destination,
                ]
            ),
            $this->scopes
        );
    }
}
