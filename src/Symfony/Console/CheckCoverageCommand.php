<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Symfony\Console;

use Ferror\OpenapiCoverage\RouteCollection;
use Ferror\OpenapiCoverage\CoverageCalculator;
use Ferror\OpenapiCoverage\Route;
use Nelmio\ApiDocBundle\Render\RenderOpenApi;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

class CheckCoverageCommand extends Command
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly RenderOpenApi $renderOpenApi,
        private readonly ?LoggerInterface $logger = null,
        private readonly array $excludedPaths = [],
        private readonly string $prefix = '/v1',
    ) {
        parent::__construct('ferror:check-openapi-coverage');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $paths = [];

        foreach ($this->router->getRouteCollection()->getIterator() as $route) {
            foreach ($route->getMethods() as $method) {
                $paths[] = new Route($route->getPath(), $method);
            }
        }

        $this->logger?->debug('CoverageCommand: Open Api Paths ', ['open_api_paths' => $paths]);

        $paths = RouteCollection::create($paths)
            ->filter(fn (Route $route) => str_starts_with($route->path, $this->prefix))
            ->map(fn (Route $route) => new Route(str_replace($this->prefix, '', $route->path), $route->method))
            ->filter(fn(Route $route) => !in_array($route->path, $this->excludedPaths, true))
        ;

        $openApi = $this->renderOpenApi->render('json', 'default');
        $openApi = json_decode($openApi, true, 512, JSON_THROW_ON_ERROR);

        $openApiPaths = [];
        foreach ($openApi['paths'] as $path => $methods) {
            foreach (array_keys($methods) as $method) {
                $openApiPaths[] = new Route($path, $method);
            }
        }

        $openApiPaths = RouteCollection::create($openApiPaths);

        $this->logger?->debug('CoverageCommand: Open Api Paths ', ['open_api_paths_count' => $openApiPaths->count()]);

        $missingPaths = RouteCollection::create();

        // Calculate not documented paths
        foreach ($openApiPaths->items as $path) {
            if (!$paths->contains($path)) {
                $missingPaths = $missingPaths->add($path);
            }
        }

        $notExistingPaths = RouteCollection::create();

        // Calculate not existing, documented paths
        foreach ($paths->items as $path) {
            if (!$openApiPaths->contains($path)) {
                $notExistingPaths = $notExistingPaths->add($path);
            }
        }

        $coverageCalculator = new CoverageCalculator($paths->count(), $openApiPaths->count() - $notExistingPaths->count());

        $output->writeln('Open API coverage: ' . $coverageCalculator->calculate()->asPercentage() . '%');

        if ($missingPaths->count() === 0) {
            $output->writeln('OpenAPI schema covers all Symfony routes. Good job!');
        } else {
            $table = new Table($output);
            $table->setHeaderTitle('Missing documentation');
            $table->setHeaders(['path', 'method']);

            foreach ($missingPaths->items as $route) {
                $table->addRow([$route->path, $route->method]);
            }

            $table->render();
        }

        return Command::SUCCESS;
    }
}
