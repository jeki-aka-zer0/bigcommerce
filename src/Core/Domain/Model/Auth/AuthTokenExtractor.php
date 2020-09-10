<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use Bigcommerce\Api\Client;
use stdClass;

final class AuthTokenExtractor
{
    private CredentialsDto $credentials;

    private $response;

    public function __construct(CredentialsDto $credentials)
    {
        $this->credentials = $credentials;
    }

    public function getHash(): Hash
    {
        $chunks = explode('/', $this->getResponse()->context, 2);

        if (empty($chunks[1])) {
            throw new WrongHashException();
        }

        return new Hash($chunks[1]);
    }

    public function getResponse(): stdClass
    {
        if (null === $this->response) {
            $this->response = Client::getAuthToken($this->credentials);

            if (false === $this->response) {
                throw new WrongResponseException();
            }
        }

        return $this->response;
    }
}
