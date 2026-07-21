<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Tests\Unit;

use OpenMeta\Extensions\Exceptions\DependencyException;
use OpenMeta\Extensions\Manifest\Dependency;
use OpenMeta\Extensions\Support\DependencyResolver;
use OpenMeta\Extensions\Tests\ExtensionsTestCase;

final class DependencyResolverTest extends ExtensionsTestCase
{
    public function test_orders_dependencies_before_dependents(): void
    {
        $a = $this->manifest('acme/a', dependencies: [new Dependency('acme/b')]);
        $b = $this->manifest('acme/b', dependencies: [new Dependency('acme/c')]);
        $c = $this->manifest('acme/c');

        $ordered = (new DependencyResolver())->resolve([$a, $b, $c]);
        $ids = array_map(static fn ($manifest): string => $manifest->packageId(), $ordered);

        self::assertSame(['acme/c', 'acme/b', 'acme/a'], $ids);
    }

    public function test_missing_required_dependency_throws(): void
    {
        $this->expectException(DependencyException::class);

        (new DependencyResolver())->resolve([
            $this->manifest('acme/a', dependencies: [new Dependency('acme/absent')]),
        ]);
    }

    public function test_missing_optional_dependency_is_ignored(): void
    {
        $ordered = (new DependencyResolver())->resolve([
            $this->manifest('acme/a', dependencies: [new Dependency('acme/absent', '*', true)]),
        ]);

        self::assertCount(1, $ordered);
        self::assertSame('acme/a', $ordered[0]->packageId());
    }

    public function test_circular_dependency_throws(): void
    {
        $this->expectException(DependencyException::class);

        (new DependencyResolver())->resolve([
            $this->manifest('acme/a', dependencies: [new Dependency('acme/b')]),
            $this->manifest('acme/b', dependencies: [new Dependency('acme/a')]),
        ]);
    }
}
