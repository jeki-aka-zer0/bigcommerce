<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model;

use Bigcommerce\Api\Client;
use Src\Core\Domain\Model\Auth\Integration;

final class ClientConfigurator
{
    protected string $clientId;

    public function __construct(string $clientId)
    {
        $this->clientId = $clientId;
    }

    public function configureV2(Integration $integration): void
    {
        $this->configure($integration);
    }

    public function configureV3(Integration $integration): void
    {
        $this->configure($integration);

        Client::$api_path = 'https://api.bigcommerce.com/stores/' . $integration->getStoreHash()->getHash() . '/v3';

        var_dump(Client::$api_path);
    }

    public function configure(Integration $integration): void
    {
        Client::configure([
            'client_id' => $this->clientId,
            'auth_token' => $integration->getAccessToken(),
            'store_hash' => $integration->getStoreHash()->getHash(),
        ]);

        var_dump([
            'client_id' => $this->clientId,
            'auth_token' => $integration->getAccessToken(),
            'store_hash' => $integration->getStoreHash()->getHash(),
        ]);
    }
}
