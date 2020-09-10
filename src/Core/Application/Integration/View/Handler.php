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

    protected LoadBodyExtractor $loadBodyExtractor;

    protected IntegrationRepository $integrationRepository;

    public function __construct(LoadBodyExtractor $loadBodyExtractor, IntegrationRepository $integrationRepository)
    {
        $this->loadBodyExtractor = $loadBodyExtractor;
        $this->integrationRepository = $integrationRepository;
    }

    public function handle(Command $command): Integration
    {
        $payload = $this->loadBodyExtractor->extract($command->getSignedPayload());
        $storeHash = $payload['store_hash'] ?? null;

        if (empty($storeHash)) {
            throw new WrongLoadPayloadException();
        }

        $integration = $this->integrationRepository->findByStoreHash(new Hash($storeHash));

        if (null === $integration) {
            throw new WrongLoadPayloadException();
        }

        return $integration;
    }
}
