<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Webhook;

use Bigcommerce\Api\Client;
use Src\Core\Domain\Model\Api\WrongResponseException;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class WebhookManager
{
    private ClientConfigurator $clientConfigurator;

    private array $scopes;

    private string $destination;

    public function __construct(ClientConfigurator $clientConfigurator, array $scopes, string $destination)
    {
        $this->scopes = $scopes;
        $this->destination = $destination;
        $this->clientConfigurator = $clientConfigurator;
    }

    public function subscribe(Integration $integration): void
    {
        $this->clientConfigurator->configureV2($integration);

        var_dump(Client::listWebhooks());
        exit;

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
