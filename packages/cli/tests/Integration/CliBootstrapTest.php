<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Integration;

use OpenMeta\Cli\Application\ConsoleApplication;
use OpenMeta\Cli\CliServiceProvider;
use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Cli\Contracts\ConsoleApplicationInterface;
use OpenMeta\Cli\Output\BufferedOutput;
use OpenMeta\Cli\Output\OutputFormatter;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Core\Bootstrap\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * Boots the framework with the CLI provider and runs a command end-to-end
 * (through a buffered output, never STDOUT).
 */
final class CliBootstrapTest extends TestCase
{
    public function test_built_in_commands_are_registered(): void
    {
        $app = Bootstrap::run([], [CliServiceProvider::class]);
        /** @var CommandRegistryInterface $registry */
        $registry = $app->get(CommandRegistryInterface::class);

        foreach (['version', 'info', 'doctor', 'list', 'help', 'make:command'] as $name) {
            self::assertTrue($registry->has($name), "missing built-in command [{$name}]");
        }

        self::assertInstanceOf(ConsoleApplicationInterface::class, $app->get('cli'));
    }

    public function test_runs_version_command_through_wired_registry(): void
    {
        $app = Bootstrap::run([], [CliServiceProvider::class]);
        /** @var CommandRegistryInterface $registry */
        $registry = $app->get(CommandRegistryInterface::class);

        $output = new BufferedOutput(new OutputFormatter(false));
        $console = new ConsoleApplication($registry, $output, $app->events());

        $exit = $console->run(['version']);

        self::assertSame(ExitCode::SUCCESS, $exit);
        self::assertStringContainsString('OpenMeta', $output->content());
    }
}
