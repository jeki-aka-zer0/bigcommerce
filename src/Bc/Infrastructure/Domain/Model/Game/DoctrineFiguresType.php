<?php

declare(strict_types=1);

namespace Src\Bc\Infrastructure\Domain\Model\Game;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Src\Bc\Domain\Model\Game\Figures;

final class DoctrineFiguresType extends Type
{
    public const NAME = 'figures';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof Figures ? $value->getFigures() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Figures
    {
        return empty($value) ? null : new Figures($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $fieldDeclaration['length'] = Figures::LENGTH;
        $fieldDeclaration['fixed'] = true;

        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
