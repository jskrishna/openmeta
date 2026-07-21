<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Tests\Unit;

use OpenMeta\Generator\Exceptions\GeneratorNotFoundException;
use OpenMeta\Generator\Registry\GeneratorDefinitions;
use OpenMeta\Generator\Registry\GeneratorFactory;
use OpenMeta\Generator\Registry\GeneratorRegistry;
use OpenMeta\Generator\Resolvers\NamespaceResolver;
use OpenMeta\Generator\Resolvers\PlaceholderResolver;
use OpenMeta\Generator\Stubs\StubLoader;
use OpenMeta\Generator\Templates\TemplateEngine;
use OpenMeta\Support\Filesystem\LocalFilesystem;
use PHPUnit\Framework\TestCase;

final class GeneratorRegistryTest extends TestCase
{
    private function registry(): GeneratorRegistry
    {
        $factory = new GeneratorFactory(
            new StubLoader(new LocalFilesystem(), []),
            new TemplateEngine(),
            new PlaceholderResolver(),
            new NamespaceResolver(),
        );

        $registry = new GeneratorRegistry();
        foreach ($factory->defaults() as $generator) {
            $registry->register($generator);
        }

        return $registry;
    }

    public function test_defaults_cover_all_types(): void
    {
        $registry = $this->registry();

        self::assertCount(count(GeneratorDefinitions::all()), $registry->all());
        self::assertTrue($registry->has('field'));
        self::assertTrue($registry->has('graphql-type'));
        self::assertTrue($registry->has('extension'));
    }

    public function test_keys_are_sorted(): void
    {
        $keys = $this->registry()->keys();
        $sorted = $keys;
        sort($sorted);

        self::assertSame($sorted, $keys);
    }

    public function test_unknown_generator_throws(): void
    {
        $this->expectException(GeneratorNotFoundException::class);
        $this->registry()->get('nope');
    }
}
