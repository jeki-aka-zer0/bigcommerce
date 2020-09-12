<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Bigcommerce\Api\Client;
use Src\Core\Domain\Model\Api\WrongResponseException;

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
            function(string $scope) {
                $response = Client::createWebhook(
                    [
                        'scope' => $scope,
                        'destination' => $this->destination,
                    ]
                );

                if (false === $response) {
                    throw new WrongResponseException('hook: ' . $scope . ' - ' . json_encode(Client::getLastError()));
                }
            },
            $this->scopes
        );
    }
}
