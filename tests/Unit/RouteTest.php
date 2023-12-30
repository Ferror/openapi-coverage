<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Unit;

use Ferror\OpenapiCoverage\Route;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function testEquals(): void
    {
        $route = new Route('products', 'get');

        $this->assertTrue($route->equals(new Route('products', 'get')));
        $this->assertFalse($route->equals(new Route('products', 'post')));
    }

    public function testEqualsWithDifferentCase(): void
    {
        $route = new Route('products', 'get');

        $this->assertTrue($route->equals(new Route('products', 'GET')));
    }
}
