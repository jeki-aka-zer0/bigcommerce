<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Update;

use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Script\ScriptManager;
use Src\Core\Domain\Model\Webhook\WebhookHandler;
use Src\Core\Domain\Model\FlusherInterface;

final class Handler
{
    private FlusherInterface $flusher;

    private IntegrationRepository $integrationRepository;

    private WebhookHandler $webhookManager;

    private ScriptManager $scriptManager;

    public function __construct(
        IntegrationRepository $integrationRepository,
        FlusherInterface $flusher,
        WebhookHandler $webhookManager,
        ScriptManager $scriptManager
    ) {
        $this->integrationRepository = $integrationRepository;
        $this->flusher = $flusher;
        $this->webhookManager = $webhookManager;
        $this->scriptManager = $scriptManager;
    }

    public function handle(Command $command): void
    {
        // @todo UNSECURE
        $integration = $this->integrationRepository->getByStoreHash(new Hash($command->getStoreHash()));

        $integration->setApiKey($command->getApiKey());

        $this->webhookManager->subscribe($integration);
        $this->scriptManager->addToStore($integration);

        $this->flusher->flush($integration);
    }
}
