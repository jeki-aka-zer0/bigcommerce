<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Update;

use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Script\ScriptManager;
use Src\Core\Domain\Model\Webhook\WebhookManager;
use Src\Core\Domain\Model\FlusherInterface;

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

        $this->webhookManager->subscribe($integration);
        $this->scriptManager->addToStore($integration);

        $integration->setApiKey($command->getApiKey());

        $this->flusher->flush($integration);
    }
}
