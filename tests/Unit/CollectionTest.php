<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Unit;

use Ferror\OpenapiCoverage\Collection;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    public function testFilter(): void
    {
        $collection = new Collection(['item', 'not-item']);

        $collection = $collection->filter(fn (string $item) => $item === 'item');

        $this->assertEquals(['item'], $collection->items);
    }

    public function testDiff(): void
    {
        $collection = new Collection(['item-1', 'item-2']);

        $collection = $collection->diff(['item-1']);

        $this->assertEquals(['item-2'], $collection->items);
    }

    public function testMap(): void
    {
        $collection = new Collection(['item', 'item']);

        $collection = $collection->map(fn (string $item) => $item . '-not');

        $this->assertEquals(['item-not', 'item-not'], $collection->items);
    }
}
