<?php

declare(strict_types=1);

namespace Src\Core\Application\Webhook\Receive;

final class Handler
{
    public function handle(Command $command): void
    {
        // @todo
        $log = new \Monolog\Logger('name');
        $log->pushHandler(new \Monolog\Handler\StreamHandler(ROOT_DIR . '/var/log/wh.log'));
        $log->warning(serialize($command->getScore()).serialize($command->getData()));
    }
}
