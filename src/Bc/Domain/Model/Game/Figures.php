<?php

declare(strict_types=1);

namespace Src\Bc\Domain\Model\Game;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

final class Figures
{
    public const LENGTH = 4;

    private string $figures;

    /**
     * Figures constructor.
     *
     * @param string $figuresRaw
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $figuresRaw)
    {
        $figures = str_split($figuresRaw);
        $lengthUnique = \count(array_unique($figures));
        $length = \count($figures);
        Assert::digits($figuresRaw, 'figures.only_digits_allowed');
        Assert::allEq([$length, $lengthUnique], self::LENGTH, 'figures.must_consist_of_4_unique_figures');
        $this->figures = $figuresRaw;
    }

    public static function generate(): self
    {
        $array = array_rand(range(0, 9), self::LENGTH);
        shuffle($array);
        $figures = implode('', $array);

        return new static($figures);
    }

    public function getFigures(): string
    {
        return $this->figures;
    }

    public function __toString(): string
    {
        return $this->getFigures();
    }

    public function compare(Figures $figures): Result
    {
        $guess = $figures->getFigures();
        $answer = $this->getFigures();
        $bulls = $cows = 0;

        foreach (range(0, self::LENGTH - 1) as $i) {
            if ($answer[$i] === $guess[$i]) {
                $bulls++;
            } elseif (false !== strpos($guess, $answer[$i])) {
                $cows++;
            }
        }

        return new Result($bulls, $cows);
    }
}
