<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Integration\Service;

use Ferror\OpenapiCoverage\Symfony\Bundle;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;

class Kernel extends SymfonyKernel
{
    use MicroKernelTrait;
    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new NelmioApiDocBundle();
        yield new Bundle();
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/framework.yaml');
        $loader->load(__DIR__ . '/config/library.yaml');
        $loader->load(__DIR__ . '/config/services.yaml');

        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', [
                'router' => [
                    'resource' => __DIR__ . '/config/routes.yaml',
                ],
            ]);
        });
    }
}
