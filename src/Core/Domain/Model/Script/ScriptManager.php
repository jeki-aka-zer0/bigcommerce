<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Script;

use Bigcommerce\Api\Client;
use Src\Core\Domain\Model\Api\WrongResponseException;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class ScriptManager
{
    private string $src;

    private ClientConfigurator $clientConfigurator;

    public function __construct(ClientConfigurator $clientConfigurator, string $src)
    {
        $this->src = $src;
        $this->clientConfigurator = $clientConfigurator;
    }

    public function addToStore(Integration $integration): void
    {
        $this->clientConfigurator->configureV3($integration);

        $response = Client::createResource('/content/scripts', [
            'name' => 'ManyChat Script',
            'src' => $this->src,
            'auto_uninstall' => true,
            'load_method' => 'default',
            'location' => 'footer',
            'visibility' => 'all_pages',
            'kind' => 'src',
        ]);

        if (false === $response) {
            throw new WrongResponseException('script - ' . json_encode(Client::getLastError()));
        }
    }
}
