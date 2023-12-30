<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Unit;

use Ferror\OpenapiCoverage\Coverage;
use Ferror\OpenapiCoverage\CoverageCalculator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CoverageCalculatorTest extends TestCase
{
    public function testCalculate(): void
    {
        $calculator = new CoverageCalculator(2, 1);

        $this->assertEquals(new Coverage(0.5), $calculator->calculate());
    }

    public function testCalculateHalf(): void
    {
        $calculator = new CoverageCalculator(1, 1);

        $this->assertEquals(new Coverage(1), $calculator->calculate());
    }

    public function testThrowsExceptionOnNegativePaths(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new CoverageCalculator(-1, 1);
    }

    public function testThrowsExceptionOnNegativeDocumentedPaths(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new CoverageCalculator(1, -1);
    }
}
