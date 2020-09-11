<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\View;

use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Load\LoadBodyExtractor;
use Src\Core\Domain\Model\Load\WrongLoadPayloadException;

final class Handler
{

    private LoadBodyExtractor $loadBodyExtractor;

    private IntegrationRepository $integrationRepository;

    private ?Integration $integration;

    private ?string $storeHash;

    public function __construct(LoadBodyExtractor $loadBodyExtractor, IntegrationRepository $integrationRepository)
    {
        $this->loadBodyExtractor = $loadBodyExtractor;
        $this->integrationRepository = $integrationRepository;
    }

    public function handle(Command $command): void
    {
        $payload = $this->loadBodyExtractor->extract($command->getSignedPayload());
        $storeHash = $payload['store_hash'] ?? null;

        if (empty($storeHash)) {
            throw new WrongLoadPayloadException();
        }

        $integration = $this->integrationRepository->getByStoreHash(new Hash($storeHash));

        $this->integration = $integration;
        $this->storeHash = $storeHash;
    }

    public function getIntegration(): Integration
    {
        return $this->integration;
    }

    public function getStoreHash(): string
    {
        return $this->storeHash;
    }
}
