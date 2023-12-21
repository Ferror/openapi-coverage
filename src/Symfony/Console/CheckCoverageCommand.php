<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Symfony\Console;

use Nelmio\ApiDocBundle\Render\RenderOpenApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class CheckCoverageCommand extends Command
{
    protected static $defaultName = 'ferror:check-openapi-coverage';

    public function __construct(
        private RouterInterface $router,
        private RenderOpenApi $renderOpenApi,
        private array $excludedPaths = [],
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Check OpenAPI coverage against Symfony routes')
            ->addArgument('openapi-schema', InputArgument::OPTIONAL, 'Path to OpenAPI schema YAML file')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyPaths = $this->router->getRouteCollection();
        $symfonyPaths = array_map(fn (Route $route) => $route->getPath(), $symfonyPaths->all());
        $symfonyPaths = array_values($symfonyPaths);
        $symfonyPaths = array_filter($symfonyPaths, fn (string $route) => str_starts_with($route, '/risk/v1'));
        $symfonyPaths = array_map(fn (string $route) => str_replace('/risk/v1', '', $route), $symfonyPaths);
        $symfonyPaths = array_filter($symfonyPaths, fn(string $route) => !in_array($route, $this->excludedPaths, true));

        $openApi = $this->renderOpenApi->render('json', 'default');
        $openApi = json_decode($openApi, true, 512, JSON_THROW_ON_ERROR);

        $openApiPaths = array_keys($openApi['paths']);

        $missingPaths = array_diff($symfonyPaths, $openApiPaths);

        $missingPathCoverage =  count($openApiPaths) / count($symfonyPaths);
        $output->writeln('Open API coverage: ' . round($missingPathCoverage * 100, 2) . '%');

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
