<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model\Job;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Src\Core\Domain\Model\Job\Sign;

final class DoctrineSignType extends StringType
{
    public const NAME = 'sign';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof Sign ? $value->getSign() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Sign
    {
        return empty($value) ? null : new Sign($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
