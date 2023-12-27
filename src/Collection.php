<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage;

final readonly class Collection
{
    public static function create(array $items): self
    {
        return new self($items);
    }

    public function __construct(public array $items)
    {
    }

    public function filter(callable $callable): self
    {
        return new self(array_filter($this->items, $callable));
    }

    public function map(callable $callable): self
    {
        return new self(array_map($callable, $this->items));
    }

    public function diff(array $items): self
    {
        return new self(array_values(array_diff($this->items, $items)));
    }
}
