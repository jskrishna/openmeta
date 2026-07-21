<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Tests;

use OpenMeta\Core\Events\EventDispatcher;
use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Files\ConflictDetector;
use OpenMeta\Generator\Files\FileGenerator;
use OpenMeta\Generator\Manager\GeneratorManager;
use OpenMeta\Generator\Registry\GeneratorFactory;
use OpenMeta\Generator\Registry\GeneratorRegistry;
use OpenMeta\Generator\Resolvers\NamespaceResolver;
use OpenMeta\Generator\Resolvers\PlaceholderResolver;
use OpenMeta\Generator\Stubs\StubLoader;
use OpenMeta\Generator\Templates\TemplateEngine;
use OpenMeta\Generator\Tests\Fixtures\InMemoryFilesystem;
use OpenMeta\Support\Filesystem\LocalFilesystem;
use PHPUnit\Framework\TestCase;

/**
 * Base case: real stubs on disk, generated output captured in memory.
 */
abstract class GeneratorTestCase extends TestCase
{
    protected InMemoryFilesystem $output;

    protected EventDispatcher $events;

    protected GeneratorManager $manager;

    protected GeneratorConfiguration $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->output = new InMemoryFilesystem();
        $this->events = new EventDispatcher();

        $stubPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'stubs';
        $loader = new StubLoader(new LocalFilesystem(), [$stubPath]);
        $namespaces = new NamespaceResolver();

        $factory = new GeneratorFactory($loader, new TemplateEngine(), new PlaceholderResolver(), $namespaces);
        $registry = new GeneratorRegistry();
        foreach ($factory->defaults() as $generator) {
            $registry->register($generator);
        }

        $this->config = new GeneratorConfiguration('App', 'src');

        $this->manager = new GeneratorManager(
            $registry,
            new FileGenerator($this->output),
            new ConflictDetector($this->output, $namespaces),
            $this->config,
            $this->events,
        );
    }

    /**
     * @param class-string $event
     * @param list<object> $sink
     */
    protected function capture(string $event, array &$sink): void
    {
        $this->events->listen($event, function (object $e) use (&$sink): void {
            $sink[] = $e;
        });
    }
}
