<?php

declare(strict_types=1);

namespace Ferror\OpenapiCoverage\Integration;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CheckCoverageCommandTest extends KernelTestCase
{
    public function testExecuteClass(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('ferror:check-openapi-coverage');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $display = $commandTester->getDisplay();

        $expectedDisplay = <<<TEXT
Open API coverage: 75%
+- Missing documenta... -+
| path          | method |
+---------------+--------+
| /products/:id | get    |
+---------------+--------+

TEXT;

        $this->assertEquals($expectedDisplay, $display);
    }

    public function testPositiveThreshold(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('ferror:check-openapi-coverage');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['--threshold' => 0.70]);

        $commandTester->assertCommandIsSuccessful();
    }

    public function testNegativeThreshold(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('ferror:check-openapi-coverage');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['--threshold' => 0.80]);

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
    }
}
