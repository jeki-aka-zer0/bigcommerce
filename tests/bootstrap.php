<?php

declare(strict_types=1);

use Src\Bc\Infrastructure\Ui\Shared\AppBuilder\EnvLoader;

!defined('ROOT_DIR') && define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR . '/vendor/autoload.php';

(new EnvLoader())->load();
