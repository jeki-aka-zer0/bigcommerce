<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Uninstall;

use Src\Core\Domain\Model\Auth\Hash;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\LoadBodyExtractor;
use Src\Core\Domain\Model\WrongLoadPayloadException;
use Src\Core\Infrastructure\Domain\Model\DoctrineRemover;

final class Handler
{
    private LoadBodyExtractor $loadBodyExtractor;

    private IntegrationRepository $integrationRepository;

    private DoctrineRemover $doctrineRemover;

    private FlusherInterface $doctrineFlusher;

    public function __construct(
        LoadBodyExtractor $loadBodyExtractor,
        IntegrationRepository $integrationRepository,
        DoctrineRemover $doctrineRemover,
        FlusherInterface $doctrineFlusher
    ) {
        $this->loadBodyExtractor = $loadBodyExtractor;
        $this->integrationRepository = $integrationRepository;
        $this->doctrineRemover = $doctrineRemover;
        $this->doctrineFlusher = $doctrineFlusher;
    }

    public function handle(Command $command): void
    {
        $payload = $this->loadBodyExtractor->extract($command->getSignedPayload());
        $storeHash = $payload['store_hash'] ?? null;

        if (empty($storeHash)) {
            throw new WrongLoadPayloadException();
        }

        $integration = $this->integrationRepository->getByStoreHash(new Hash($storeHash));

        if (null === $integration) {
            throw new WrongLoadPayloadException();
        }

        $this->doctrineRemover->remove($integration);
        $this->doctrineFlusher->flush();
    }
}
