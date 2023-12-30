<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Unit;

use Ferror\OpenapiCoverage\Route;
use Ferror\OpenapiCoverage\RouteCollection;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    public function testFilter(): void
    {
        $collection = new RouteCollection([new Route('products', 'get'), new Route('products', 'post')]);

        $collection = $collection->filter(fn (Route $item) => $item->path !== 'products');

        $this->assertEquals([], $collection->items);
    }

    public function testMap(): void
    {
        $collection = new RouteCollection([new Route('products', 'get'), new Route('products', 'post')]);

        $collection = $collection->map(fn (Route $item) => new Route($item->path . '-not', $item->method));

        $this->assertEquals([new Route('products-not', 'get'), new Route('products-not', 'post')], $collection->items);
    }

    public function testContains(): void
    {
        $collection = new RouteCollection([new Route('products', 'get'), new Route('products', 'post')]);

        $this->assertTrue($collection->contains(new Route('products', 'get')));
        $this->assertFalse($collection->contains(new Route('products', 'delete')));
    }
}
