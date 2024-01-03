<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage;

use InvalidArgumentException;

final readonly class Coverage
{
    public function __construct(public float $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Coverage value cannot be negative number');
        }
    }

    public function asPercentage(): float
    {
        return round($this->value * 100, 2);
    }

    public function isGreater(self $self): bool
    {
        return $this->value > $self->value;
    }

    public function isLower(self $self): bool
    {
        return $this->value < $self->value;
    }
}
