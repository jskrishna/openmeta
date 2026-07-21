<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Tests\Unit;

use OpenMeta\Extensions\Exceptions\InvalidVersionException;
use OpenMeta\Extensions\Tests\ExtensionsTestCase;
use OpenMeta\Extensions\Versioning\Version;

final class VersionTest extends ExtensionsTestCase
{
    public function test_parses_partial_and_prefixed_versions(): void
    {
        $version = Version::parse('v1.2');

        self::assertSame(1, $version->major);
        self::assertSame(2, $version->minor);
        self::assertSame(0, $version->patch);
        self::assertSame('1.2.0', (string) $version);
    }

    public function test_ignores_build_metadata(): void
    {
        self::assertSame(0, Version::parse('1.0.0+build.5')->compareTo(Version::parse('1.0.0')));
    }

    public function test_orders_core_versions(): void
    {
        self::assertSame(-1, Version::parse('1.2.3')->compareTo(Version::parse('1.3.0')));
        self::assertSame(1, Version::parse('2.0.0')->compareTo(Version::parse('1.9.9')));
    }

    public function test_prerelease_is_lower_than_release(): void
    {
        self::assertSame(-1, Version::parse('1.0.0-alpha')->compareTo(Version::parse('1.0.0')));
        self::assertSame(-1, Version::parse('1.0.0-alpha.1')->compareTo(Version::parse('1.0.0-alpha.2')));
        self::assertSame(-1, Version::parse('1.0.0-alpha')->compareTo(Version::parse('1.0.0-beta')));
    }

    public function test_rejects_empty_version(): void
    {
        $this->expectException(InvalidVersionException::class);

        Version::parse('   ');
    }

    public function test_rejects_non_numeric_version(): void
    {
        $this->expectException(InvalidVersionException::class);

        Version::parse('1.x.0');
    }
}
