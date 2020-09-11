<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use Bigcommerce\Api\Client;

final class WebhookManager
{
    private const SCOPES = ['store/cart/created', 'store/cart/updated'];

    private string $destination;

    public function __construct(string $destination)
    {
        $this->destination = $destination;
    }

    public function subscribe(): void
    {
        array_map(fn($scope) => Client::createWebhook(
            [
                'scope' => $scope,
                'destination' => $this->destination,
            ]
        ), self::SCOPES);
    }
}
