<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\View;

use Src\Core\Domain\Model\Auth\AuthTokenExtractor;
use Src\Core\Domain\Model\Auth\CredentialsDto;
use Src\Core\Domain\Model\Auth\Integration;
use Src\Core\Domain\Model\Auth\IntegrationRepository;
use Src\Core\Domain\Model\Auth\StoreAlreadyExistsException;
use Src\Core\Domain\Model\FlusherInterface;
use Src\Core\Domain\Model\Id;

final class Handler
{

    public function handle(Command $command): void
    {
        // tbd
    }
}
