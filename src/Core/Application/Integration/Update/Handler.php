<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Update;

use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Webhook\WebhookManager;
use Src\Core\Domain\Model\FlusherInterface;
use Bigcommerce\Api\Client;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class Handler
{
    private FlusherInterface $flusher;

    private IntegrationRepository $integrationRepository;

    private ClientConfigurator $clientConfigurator;

    private WebhookManager $webhookManager;

    public function __construct(IntegrationRepository $integrationRepository, FlusherInterface $flusher, ClientConfigurator $clientConfigurator, WebhookManager $webhookManager)
    {
        $this->integrationRepository = $integrationRepository;
        $this->flusher = $flusher;
        $this->clientConfigurator = $clientConfigurator;
        $this->webhookManager = $webhookManager;
    }

    public function handle(Command $command): void
    {
        // @todo UNSECURE
        $integration = $this->integrationRepository->getByStoreHash(new Hash($command->getStoreHash()));

        $this->clientConfigurator->configureV3($integration);

        $this->webhookManager->subscribe();

        // @todo errors
        // @todo make client
        Client::createResource('/content/scripts', [
            'name' => 'ManyChat Script',
            'src' => 'https://env-6234666.jelastic.regruhosting.ru/bigcommerce.js', // @todo config
            'auto_uninstall' => true,
            'load_method' => 'default',
            'location' => 'footer',
            'visibility' => 'all_pages',
            'kind' => 'src',
        ]);

        $integration->setApiKey($command->getApiKey());

        $this->flusher->flush($integration);
    }
}
