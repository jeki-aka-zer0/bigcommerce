<?php

declare(strict_types=1);

namespace Src\Core\Infrastructure\Domain\Model\Auth;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Src\Core\Domain\Model\Auth\Hash;

final class DoctrineHashType extends StringType
{
    public const NAME = 'hash';

    private const MAX_LENGTH = 255;

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof Hash ? $value->getHash() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Hash
    {
        return empty($value) ? null : new Hash($value);
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
