<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Create;

use Src\Core\Domain\Model\Auth\AuthTokenExtractor;
use Src\Core\Domain\Model\Auth\CredentialsDto;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Auth\WebhookManager;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Id;
use Src\Core\Infrastructure\Domain\Model\ClientConfigurator;

final class Handler
{
    private CredentialsDto $credentials;

    private IntegrationRepository $integrations;

    private FlusherInterface $flusher;

    private ClientConfigurator $clientConfigurator;

    private WebhookManager $webhookManager;

    public function __construct(
        CredentialsDto $credentials,
        IntegrationRepository $integrations,
        FlusherInterface $flusher,
        ClientConfigurator $clientConfigurator,
        WebhookManager $webhookManager
    ) {
        $this->credentials = $credentials;
        $this->integrations = $integrations;
        $this->flusher = $flusher;
        $this->clientConfigurator = $clientConfigurator;
        $this->webhookManager = $webhookManager;
    }

    public function handle(Command $command): void
    {
        $this->credentials->code = $command->getCode();
        $this->credentials->context = $command->getContext();
        $this->credentials->scope = $command->getScope();

        $authTokenExtractor = new AuthTokenExtractor($this->credentials);
        $storeHash = $authTokenExtractor->getHash();

        $integration = $this->integrations->findByStoreHash($storeHash);
        if (null !== $integration) {
            return;
        }

        $integration = new Integration(Id::next(), $storeHash, (array)$authTokenExtractor->getResponse());

        $this->clientConfigurator->configureV3($integration);

        $this->webhookManager->subscribe();

        $this->integrations->add($integration);

        $this->flusher->flush($integration);
    }
}
