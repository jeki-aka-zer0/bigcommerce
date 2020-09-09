<?php

declare(strict_types=1);

namespace Src\Core\Application\Integration\Create;

final class Command
{
    private string $code;

    private string $context;

    private string $scope;

    public function __construct(string $code, string $context, string $scope)
    {
        $this->code = $code;
        $this->context = $context;
        $this->scope = $scope;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getScope(): string
    {
        return $this->scope;
    }
}
