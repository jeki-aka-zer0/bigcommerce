<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model;

use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

final class Id
{
    private string $id;

    /**
     * Id constructor.
     *
     * @param string $idRaw
     * @throws InvalidArgumentException
     */
    public function __construct(string $idRaw)
    {
        $this->id = trim($idRaw);
        Assert::notEmpty($this->id);
    }

    /**
     * @return static
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public static function next(): self
    {
        return new static(Uuid::uuid4()->toString());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
