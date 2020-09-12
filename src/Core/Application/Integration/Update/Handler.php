<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Update;

use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Script\ScriptManager;
use Src\Core\Domain\Model\Webhook\WebhookManager;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class Handler
{
    private FlusherInterface $flusher;

    private IntegrationRepository $integrationRepository;

    private ClientConfigurator $clientConfigurator;

    private WebhookManager $webhookManager;

    private ScriptManager $scriptManager;

    public function __construct(
        IntegrationRepository $integrationRepository,
        FlusherInterface $flusher,
        ClientConfigurator $clientConfigurator,
        WebhookManager $webhookManager,
        ScriptManager $scriptManager
    ) {
        $this->integrationRepository = $integrationRepository;
        $this->flusher = $flusher;
        $this->clientConfigurator = $clientConfigurator;
        $this->webhookManager = $webhookManager;
        $this->scriptManager = $scriptManager;
    }

    public function handle(Command $command): void
    {
        // @todo UNSECURE
        $integration = $this->integrationRepository->getByStoreHash(new Hash($command->getStoreHash()));

        $this->clientConfigurator->configureV3($integration);

        $this->webhookManager->subscribe();
        $this->scriptManager->addToStore();

        $integration->setApiKey($command->getApiKey());

        $this->flusher->flush($integration);
    }
}
