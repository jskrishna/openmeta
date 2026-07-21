<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Tests\Unit;

use OpenMeta\Sdk\Compatibility\CompatibilityChecker;
use OpenMeta\Sdk\Compatibility\Environment;
use OpenMeta\Sdk\Manifest\Dependency;
use OpenMeta\Sdk\Manifest\Requirements;
use OpenMeta\Sdk\Tests\SdkTestCase;
use OpenMeta\Sdk\Versioning\VersionComparator;

final class CompatibilityCheckerTest extends SdkTestCase
{
    private CompatibilityChecker $checker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->checker = new CompatibilityChecker(new VersionComparator());
    }

    public function test_compatible_within_core_window(): void
    {
        $manifest = $this->manifest('acme/a', minCore: '0.10.0', maxCore: '1.0.0');
        $environment = new Environment('0.13.0', '8.3.0');

        self::assertTrue($this->checker->isCompatible($manifest, $environment));
    }

    public function test_core_below_minimum_is_incompatible(): void
    {
        $manifest = $this->manifest('acme/a', minCore: '0.20.0');
        $report = $this->checker->check($manifest, new Environment('0.13.0', '8.3.0'));

        self::assertFalse($report->compatible);
        self::assertNotEmpty($report->issues);
    }

    public function test_missing_php_extension_is_incompatible(): void
    {
        $manifest = $this->manifest(
            'acme/a',
            requirements: new Requirements(null, null, ['ext-does-not-exist']),
        );
        $environment = new Environment('0.13.0', '8.3.0', null, ['json']);

        self::assertFalse($this->checker->isCompatible($manifest, $environment));
    }

    public function test_requires_wordpress_but_none_present(): void
    {
        $manifest = $this->manifest('acme/a', requirements: new Requirements(null, '>=6.4'));
        $report = $this->checker->check($manifest, new Environment('0.13.0', '8.3.0'));

        self::assertFalse($report->compatible);
    }

    public function test_required_dependency_absent_is_incompatible(): void
    {
        $manifest = $this->manifest('acme/a', dependencies: [new Dependency('acme/b', '^1.0')]);
        $report = $this->checker->check($manifest, new Environment('0.13.0', '8.3.0'));

        self::assertFalse($report->compatible);
    }

    public function test_dependency_version_mismatch_is_incompatible(): void
    {
        $manifest = $this->manifest('acme/a', dependencies: [new Dependency('acme/b', '^2.0')]);
        $environment = new Environment('0.13.0', '8.3.0', null, [], ['acme/b' => '1.5.0']);

        self::assertFalse($this->checker->isCompatible($manifest, $environment));
    }
}
