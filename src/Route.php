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
        return $this->path === $self->path && $this->method === $self->method;
    }
}
