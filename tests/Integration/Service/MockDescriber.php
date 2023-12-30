<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Integration\Service;

use Nelmio\ApiDocBundle\Describer\DescriberInterface;
use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use OpenApi\Annotations\OpenApi;
use Symfony\Component\Yaml\Yaml;

final readonly class MockDescriber implements DescriberInterface
{
    public function describe(OpenApi $api): void
    {
        $schema = Yaml::parseFile(__DIR__ . '/resources/openapi.yaml');

        Util::merge($api, $schema, true);
    }
}
