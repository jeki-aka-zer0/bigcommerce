<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Update;

use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Script\ScriptManager;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Webhook\WebhookManager;

final class Handler
{
    private FlusherInterface $flusher;

    private IntegrationRepository $integrationRepository;

    private WebhookManager $webhookManager;

    private ScriptManager $scriptManager;

    public function __construct(
        IntegrationRepository $integrationRepository,
        FlusherInterface $flusher,
        WebhookManager $webhookManager,
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

        $integration->setTriggerApiKey($command->getTriggerApiKey());

        $this->webhookManager->subscribe($integration);
        $this->scriptManager->addToStore($integration);

        $this->flusher->flush($integration);
    }
}
