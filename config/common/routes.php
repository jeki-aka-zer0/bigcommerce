<?php

declare(strict_types=1);

use Src\Core\Infrastructure\Ui\Web\Action;

return [
    Action\Home\Action::class => fn() => new Action\Home\Action(getenv('APP_NAME')),
    Action\BigCommerce\Auth\Action::class => fn() => new Action\BigCommerce\Auth\Action(),
];
