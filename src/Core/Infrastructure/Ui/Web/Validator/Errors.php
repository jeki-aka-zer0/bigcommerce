<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Ui\Web\Validator;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class Errors
{
    private ConstraintViolationListInterface $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
    }

    public function __toString(): string
    {
        return implode('; ', array_map(fn($errors) => implode(', ', $errors), $this->toArray()));
    }

    public function toArray(): array
    {
        $errors = [];
        foreach ($this->violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}
