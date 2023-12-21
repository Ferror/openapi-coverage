<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Integration\Service;

use Ferror\OpenapiCoverage\Symfony\Bundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;

class Kernel extends SymfonyKernel
{
    public function registerBundles(): iterable
    {
        yield new Bundle();
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/library.yaml');
    }
}
