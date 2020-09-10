<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\View;

use Src\Core\Domain\Model\Auth\LoadBodyExtractor;

final class Handler
{

    protected LoadBodyExtractor $loadBodyExtractor;

    public function __construct(LoadBodyExtractor $loadBodyExtractor)
    {
        $this->loadBodyExtractor = $loadBodyExtractor;
    }

    public function handle(Command $command): void
    {
        var_dump($this->loadBodyExtractor->extract($command->getSignedPayload()));
    }
}
