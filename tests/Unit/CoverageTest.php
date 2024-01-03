<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Unit;

use Ferror\OpenapiCoverage\Coverage;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CoverageTest extends TestCase
{
    public function testThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Coverage(-1);
    }

    public function testIsGreater(): void
    {
        $coverage = new Coverage(50.0);

        $this->assertTrue($coverage->isGreater(new Coverage(40.0)));
        $this->assertTrue($coverage->isGreater(new Coverage(10.0)));

        $this->assertFalse($coverage->isGreater(new Coverage(50.0)));
        $this->assertFalse($coverage->isGreater(new Coverage(60.0)));
        $this->assertFalse($coverage->isGreater(new Coverage(100.0)));
    }

    public function testIsLower(): void
    {
        $coverage = new Coverage(50.0);

        $this->assertFalse($coverage->isLower(new Coverage(40.0)));
        $this->assertFalse($coverage->isLower(new Coverage(10.0)));
        $this->assertFalse($coverage->isLower(new Coverage(50.0)));

        $this->assertTrue($coverage->isLower(new Coverage(60.0)));
        $this->assertTrue($coverage->isLower(new Coverage(100.0)));
    }
}
