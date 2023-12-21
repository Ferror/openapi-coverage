<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Symfony;

use Ferror\OpenapiCoverage\Symfony\Console\CheckCoverageCommand;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class Bundle extends AbstractBundle
{
    protected string $extensionAlias = 'ferror_openapi_coverage';

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder
            ->register('ferror.openapi_coverage.console', CheckCoverageCommand::class)
            ->addArgument(new Reference('router'))
            ->addArgument(new Reference('nelmio_api_doc.render_docs'))
            ->addTag('console.command')
        ;
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('excluded_routes')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;
    }
}
