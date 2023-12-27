<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Symfony\Console;

use Ferror\OpenapiCoverage\Collection;
use Ferror\OpenapiCoverage\CoverageCalculator;
use Ferror\OpenapiCoverage\Route;
use Nelmio\ApiDocBundle\Render\RenderOpenApi;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
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

        $paths = Collection::create($paths)
            ->filter(fn (Route $route) => str_starts_with($route->path, $this->prefix))
            ->map(fn (Route $route) => new Route(str_replace($this->prefix, '', $route->path), $route->method))
            ->filter(fn(Route $route) => !in_array($route->path, $this->excludedPaths, true))
        ;

        $openApi = $this->renderOpenApi->render('json', 'default');
        $openApi = json_decode($openApi, true, 512, JSON_THROW_ON_ERROR);

        $openApiPaths = array_keys($openApi['paths']);

        $this->logger?->debug('CoverageCommand: Open Api Paths ', ['open_api_paths_count' => count($openApiPaths)]);

        $missingPaths = array_diff($paths->items, $openApiPaths);

        $coverageCalculator = new CoverageCalculator(count($paths->items), count($openApiPaths));

        $output->writeln('Open API coverage: ' . $coverageCalculator->calculate()->asPercentage() . '%');

        if (empty($missingPaths)) {
            $output->writeln('OpenAPI schema covers all Symfony routes. Good job!');
        } else {
            $output->writeln('Missing paths in OpenAPI schema:');
            foreach ($missingPaths as $path) {
                $output->writeln("- $path");
            }
        }

        return Command::SUCCESS;
    }
}
