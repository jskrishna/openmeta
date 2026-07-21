<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Unit;

use OpenMeta\Cli\Environment\EnvironmentInspector;
use PHPUnit\Framework\TestCase;

final class EnvironmentInspectorTest extends TestCase
{
    private EnvironmentInspector $inspector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inspector = new EnvironmentInspector();
    }

    public function test_reports_php_version_and_extensions(): void
    {
        self::assertSame(PHP_VERSION, $this->inspector->phpVersion());
        self::assertTrue($this->inspector->hasExtension('Core'));
        self::assertFalse($this->inspector->hasExtension('nonexistent_extension_xyz'));
        self::assertTrue($this->inspector->meetsMinimumPhp());
    }

    public function test_checks_include_php_and_packages(): void
    {
        $checks = $this->inspector->checks();

        self::assertNotEmpty($checks);
        $names = array_map(static fn ($check): string => $check->name, $checks);
        self::assertContains('openmeta/core', $names);
    }

    public function test_package_status_detects_core(): void
    {
        self::assertTrue($this->inspector->packageStatus()['core']);
    }
}
