<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage;

use InvalidArgumentException;

final readonly class CoverageCalculator
{
    public function __construct(
        private int $numberOfPaths,
        private int $numberOfDocumentedPaths,
    ) {
        if ($numberOfPaths < 0 || $numberOfDocumentedPaths < 0) {
            throw new InvalidArgumentException();
        }
    }

    public function calculate(): Coverage
    {
        if ($this->numberOfPaths <= 0) {
            return new Coverage(0.0);
        }

        return new Coverage($this->numberOfDocumentedPaths / $this->numberOfPaths);
    }
}
