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

//        $authTokenResponse = Client::getAuthToken($this->credentials);

        $context = array_merge(['grant_type' => 'authorization_code'], (array)$this->credentials);
        $connection = new Connection();
        $authTokenResponse = $connection->post('https://login.bigcommerce.com' . '/oauth2/token', $context);

        // create a log channel
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler(ROOT_DIR . '/var/log/app.log'));
        $log->warning(serialize($this->credentials));
        $log->warning(serialize($authTokenResponse));
        $log->warning(serialize($connection->getLastError()));


        /*$this->integrations->findById();

        $integration = new Integration(Id::next());

        $this->integrations->add($integration);

        $this->flusher->flush($integration);*/
    }
}
