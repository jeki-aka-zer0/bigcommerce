<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game;

use Src\Bc\Domain\Model\CommonRuntimeException;
use Throwable;

final class GameNotFoundException extends CommonRuntimeException
{
    private const MESSAGE = 'game.not_found';

    public function __construct(string $message = self::MESSAGE, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
