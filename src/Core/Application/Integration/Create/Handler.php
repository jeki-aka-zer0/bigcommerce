<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Create;

use Src\Core\Domain\Model\Auth\AuthTokenExtractor;
use Src\Core\Domain\Model\Auth\CredentialsDto;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Auth\StoreAlreadyExistsException;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Id;

final class Handler
{
    private CredentialsDto $credentials;

    private IntegrationRepository $integrations;

    private FlusherInterface $flusher;

    public function __construct(CredentialsDto $credentials, IntegrationRepository $integrations, FlusherInterface $flusher)
    {
        $this->credentials = $credentials;
        $this->integrations = $integrations;
        $this->flusher = $flusher;
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
            throw new StoreAlreadyExistsException();
        }

        $integration = new Integration(Id::next(), $storeHash, (array)$authTokenExtractor->getResponse());

        $this->integrations->add($integration);

        $this->flusher->flush($integration);
    }
}
