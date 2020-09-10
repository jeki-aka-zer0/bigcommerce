<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Create;

use Bigcommerce\Api\Client;
use Bigcommerce\Api\Connection;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Src\Core\Application\Integration\Create\Command;
use Src\Core\Domain\Model\Auth\CredentialsDto;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Id;

final class Handler
{
    private CredentialsDto $credentials;

    private IntegrationRepository $integrations;

    private FlusherInterface $flusher;

    private Logger $logger;

    public function __construct(CredentialsDto $credentials, IntegrationRepository $integrations, FlusherInterface $flusher)
    {
        $this->credentials = $credentials;
        $this->integrations = $integrations;
        $this->flusher = $flusher;

        // todo move?
        $this->logger = new Logger('name');
        $this->logger->pushHandler(new StreamHandler(ROOT_DIR . '/var/log/app.log'));
    }

    public function handle(Command $command): void
    {
        $this->credentials->code = $command->getCode();
        $this->credentials->context = $command->getContext();
        $this->credentials->scope = $command->getScope();

        $response = Client::getAuthToken($this->credentials);
        if (!$response) {
            $this->logger->critical('Error on auth', (array) $this->credentials);

            return;
        }

        [$context, $storeHash] = explode('/', $response->context, 2);

        $integration = $this->integrations->findByStoreHash($storeHash);
        if (null !== $integration) {
            $this->logger->critical('Store already exists', ['store_hash' => $storeHash]);

            return;
        }

        $integration = new Integration(Id::next(), $storeHash, (array) $response);

        $this->integrations->add($integration);

        $this->flusher->flush($integration);
    }
}
