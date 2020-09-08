<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

final class Level
{
    public const  EASY = 'easy';
    public const  HARD = 'hard';
    private const ALL  = [self::EASY, self::HARD];

    private string $level;

    /**
     * Level constructor.
     *
     * @param string $levelRaw
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $levelRaw)
    {
        $this->level = trim($levelRaw);
        Assert::oneOf($this->level, self::ALL, 'level.easy_or_hard');
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function __toString(): string
    {
        return $this->level;
    }

    public function isHard(): bool
    {
        return self::HARD === $this->getLevel();
    }
}
