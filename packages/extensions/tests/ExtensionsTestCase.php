<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Tests;

use OpenMeta\Extensions\Manifest\Dependency;
use OpenMeta\Extensions\Manifest\Manifest;
use OpenMeta\Extensions\Manifest\Requirements;

/**
 * Base test case for @openmeta/extensions.
 */
abstract class ExtensionsTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Convenience manifest builder for tests.
     *
     * @param list<Dependency>   $dependencies
     * @param list<class-string> $providers
     */
    protected function manifest(
        string $packageId,
        string $version = '1.0.0',
        array $dependencies = [],
        array $providers = [],
        ?string $minCore = null,
        ?string $maxCore = null,
        ?Requirements $requirements = null,
    ): Manifest {
        [$vendor, $name] = str_contains($packageId, '/')
            ? explode('/', $packageId, 2)
            : ['acme', $packageId];

        return new Manifest(
            $packageId,
            $name,
            $vendor,
            $version,
            'Test extension',
            'Test Author',
            'MIT',
            $dependencies,
            $minCore,
            $maxCore,
            $providers,
            [],
            [],
            [],
            [],
            [],
            $requirements ?? new Requirements(),
        );
    }
}
