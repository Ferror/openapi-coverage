<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage;

final readonly class Route
{
    public function __construct(
        public string $path,
        public string $method,
    ) {
    }

    public function equals(self $self): bool
    {
        return strtoupper($this->path) === strtoupper($self->path)
            && strtoupper($this->method) === strtoupper($self->method);
    }
}
