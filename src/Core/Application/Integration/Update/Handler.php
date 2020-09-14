<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Update;

use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\Integration;
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

    private ?Integration $integration;

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
        $this->integration = $this->integrationRepository->getByStoreHash(new Hash($command->getStoreHash()));

        $this->integration->setTriggerApiKey($command->getTriggerApiKey());
        $this->integration->setPublicApiKey($command->getPublicApiKey());
        $this->integration->setAbandonedPeriod($command->getAbandonedPeriod());
        $this->integration->setAbandonedUnit($command->getAbandonedUnit());

        $this->webhookManager->subscribe($this->integration);
        $this->scriptManager->addToStore($this->integration);

        $this->flusher->flush($this->integration);
    }

    public function getIntegration(): Integration
    {
        return $this->integration;
    }
}
