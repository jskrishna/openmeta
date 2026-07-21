<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Exceptions\InvalidPathException;
use OpenMeta\Support\Paths\Path;

final class PathTest extends SupportTestCase
{
    public function test_join_and_normalize(): void
    {
        $joined = Path::join('foo', 'bar', 'baz.txt');
        self::assertStringContainsString('foo', $joined);
        self::assertStringEndsWith('baz.txt', $joined);
        self::assertSame('baz.txt', Path::basename($joined));
        self::assertSame('txt', Path::extension($joined));
    }

    public function test_rejects_null_bytes(): void
    {
        $this->expectException(InvalidPathException::class);
        Path::normalize("evil\0path");
    }

    public function test_is_absolute(): void
    {
        self::assertFalse(Path::isAbsolute('relative/path'));
        self::assertTrue(Path::isAbsolute('/abs'));
    }
}
