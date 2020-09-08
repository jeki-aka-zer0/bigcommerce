<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Ui\Web\Middleware;

use Laminas\Diactoros\Response\JsonResponse;

final class ErrorsJsonResponse extends JsonResponse
{
    public function __construct(
        string $errors,
        int $status = 200,
        array $headers = [],
        int $encodingOptions = self::DEFAULT_JSON_FLAGS
    ) {
        parent::__construct(
            [
                'is_victory' => false,
                'bulls' => 0,
                'cows' => 0,
                'moves_left' => 0,
                'errors' => $errors,
            ],
            $status,
            $headers,
            $encodingOptions
        );
    }
}
