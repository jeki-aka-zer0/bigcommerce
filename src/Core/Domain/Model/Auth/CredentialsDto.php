<?php

declare(strict_types=1);

namespace Src\Core\Domain\Model\Auth;

use stdClass;

final class CredentialsDto extends stdClass
{
    public string $client_id;

    public string $client_secret;

    public string $redirect_uri;

    public string $code;

    public string $context;

    public string $scope;

    public function __construct(string $client_id, string $client_secret, string $redirect_uri)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
    }
}
