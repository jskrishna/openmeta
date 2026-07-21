<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Tests\Integration;

use OpenMeta\Cli\CliServiceProvider;
use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Docgen\DocgenServiceProvider;
use OpenMeta\Docgen\Manager\DocumentationManager;
use PHPUnit\Framework\TestCase;

/**
 * Boots the CLI + docgen providers and confirms the docs:* commands mount and
 * the manager is wired.
 */
final class DocgenBootstrapTest extends TestCase
{
    public function test_docs_commands_are_registered(): void
    {
        $app = Bootstrap::run([], [CliServiceProvider::class, DocgenServiceProvider::class]);
        /** @var CommandRegistryInterface $registry */
        $registry = $app->get(CommandRegistryInterface::class);

        self::assertTrue($registry->has('docs:validate'));
        self::assertTrue($registry->has('docs:api'));
        self::assertTrue($registry->has('docs:build'));

        self::assertInstanceOf(DocumentationManager::class, $app->get('docs'));
    }
}
