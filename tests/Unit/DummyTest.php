<?php

declare(strict_types=1);

namespace Test\Unit;

use PHPUnit\Framework\TestCase;

final class DummyTest extends TestCase
{
    public function testShouldSuccess(): void
    {
        self::assertTrue(true);
    }
}
