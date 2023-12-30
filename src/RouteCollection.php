<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage;

final readonly class RouteCollection
{
    public static function create(array $items = []): self
    {
        return new self($items);
    }

    /**
     * @param Route[] $items
     */
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

    public function count(): int
    {
        return count($this->items);
    }

    public function contains(Route $route): bool
    {
        foreach ($this->items as $item) {
            if ($item->equals($route)) {
                return true;
            }
        }

        return false;
    }

    public function add(Route $route): self
    {
        return new self(array_merge($this->items, [$route]));
    }
}
