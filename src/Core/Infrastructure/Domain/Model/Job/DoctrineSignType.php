<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model\Job;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Src\Core\Domain\Model\Job\Sign;

final class DoctrineSignType extends StringType
{
    public const NAME = 'sign';

    private const MAX_LENGTH = 255;

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

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        if (null === $fieldDeclaration['length']) {
            $fieldDeclaration['length'] = self::MAX_LENGTH;
        }

        $fieldDeclaration['fixed'] = true;

        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }
}
