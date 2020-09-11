<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Update;

use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Load\WrongLoadPayloadException;

final class Handler
{
    private FlusherInterface $flusher;

    private IntegrationRepository $integrationRepository;

    public function __construct(IntegrationRepository $integrationRepository, FlusherInterface $flusher)
    {
        $this->integrationRepository = $integrationRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        // @todo UNSECURE
        $integration = $this->integrationRepository->findByStoreHash(new Hash($command->getStoreHash()));

        if (null === $integration) {
            // @todo change
            throw new WrongLoadPayloadException();
        }

        $integration->setApiKey($command->getApiKey());

        $this->flusher->flush($integration);
    }
}
