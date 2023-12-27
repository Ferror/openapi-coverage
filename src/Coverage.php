<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage;

final readonly class Coverage
{
    public function __construct(public float $value)
    {
    }

    public function asPercentage(): float
    {
        return round($this->value * 100, 2);
    }
}
