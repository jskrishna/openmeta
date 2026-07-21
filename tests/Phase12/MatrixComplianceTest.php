<?php

declare(strict_types=1);

namespace OpenMeta\Tests\Phase12;

/**
 * Ensures every package ships the Phase 12 five-layer test folders.
 */
final class MatrixComplianceTest extends \PHPUnit\Framework\TestCase
{
    private const LAYERS = [
        'Unit',
        'Integration',
        'WordPress',
        'Performance',
        'Security',
    ];

    private const PACKAGES = [
        'core',
        'support',
        'validation',
        'security',
        'database',
        'fields',
        'api',
        'ui',
        'admin',
        'builder',
        'wordpress',
    ];

    public function test_every_package_has_five_layer_suites(): void
    {
        $root = dirname(__DIR__, 2);

        foreach (self::PACKAGES as $package) {
            foreach (self::LAYERS as $layer) {
                $dir = $root . '/packages/' . $package . '/tests/' . $layer;
                $this->assertDirectoryExists($dir, sprintf('Missing %s/%s', $package, $layer));

                $tests = glob($dir . '/*Test.php') ?: [];
                $this->assertNotEmpty(
                    $tests,
                    sprintf('Package [%s] layer [%s] needs at least one *Test.php', $package, $layer)
                );
            }
        }
    }
}
