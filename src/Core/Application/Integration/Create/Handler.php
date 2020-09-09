<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Create;

use Bigcommerce\Api\Client;
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

        file_put_contents('/tmp/php-log', serialize($this->credentials));

        $authTokenResponse = Client::getAuthToken($this->credentials);

        file_put_contents('/tmp/php-log', serialize($authTokenResponse));


        /*$this->integrations->findById();

        $integration = new Integration(Id::next());

        $this->integrations->add($integration);

        $this->flusher->flush($integration);*/
    }
}
