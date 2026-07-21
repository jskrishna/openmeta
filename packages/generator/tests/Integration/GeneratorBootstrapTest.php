<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Tests\Integration;

use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Generator\Contracts\GeneratorManagerInterface;
use OpenMeta\Generator\Files\FileStatus;
use OpenMeta\Generator\GeneratorServiceProvider;
use OpenMeta\Generator\Manager\GenerationOptions;
use OpenMeta\Generator\Manager\GenerationRequest;
use OpenMeta\Cli\CliServiceProvider;
use PHPUnit\Framework\TestCase;

/**
 * Boots the CLI + generator providers and confirms `make:*` commands mount and
 * the manager runs (dry-run, so nothing is written to disk).
 */
final class GeneratorBootstrapTest extends TestCase
{
    public function test_make_commands_are_registered_in_the_cli(): void
    {
        $app = Bootstrap::run([], [CliServiceProvider::class, GeneratorServiceProvider::class]);
        /** @var CommandRegistryInterface $registry */
        $registry = $app->get(CommandRegistryInterface::class);

        self::assertTrue($registry->has('make:field'));
        self::assertTrue($registry->has('make:graphql-type'));
        self::assertTrue($registry->has('make:extension'));
    }

    public function test_manager_resolves_and_previews(): void
    {
        $app = Bootstrap::run([], [CliServiceProvider::class, GeneratorServiceProvider::class]);
        /** @var GeneratorManagerInterface $manager */
        $manager = $app->get(GeneratorManagerInterface::class);

        $result = $manager->run(new GenerationRequest('field', 'star', [], new GenerationOptions(dryRun: true)));

        self::assertSame(FileStatus::Previewed, $result->files[0]->status);
        self::assertStringContainsString('final class Star', $result->files[0]->contents);
        self::assertSame($manager, $app->get('generator'));
    }
}
