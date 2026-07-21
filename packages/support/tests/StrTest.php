<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Str\Str;

final class StrTest extends SupportTestCase
{
    public function test_length_case_and_contains(): void
    {
        self::assertSame(5, Str::length('hello'));
        self::assertSame(2, Str::length('हि'));
        self::assertSame('hello', Str::lower('HELLO'));
        self::assertSame('HELLO', Str::upper('hello'));
        self::assertTrue(Str::startsWith('openmeta', 'open'));
        self::assertTrue(Str::endsWith('openmeta', 'meta'));
        self::assertTrue(Str::contains('openmeta', 'enm'));
    }

    public function test_limit_case_styles_and_slug(): void
    {
        self::assertSame('hel...', Str::limit('hello', 3));
        self::assertSame('open_meta', Str::snake('OpenMeta'));
        self::assertSame('openMeta', Str::camel('open_meta'));
        self::assertSame('OpenMeta', Str::studly('open_meta'));
        self::assertSame('open-meta', Str::slug('Open Meta!'));
    }

    public function test_before_after_empty(): void
    {
        self::assertSame('foo', Str::before('foo.bar', '.'));
        self::assertSame('bar', Str::after('foo.bar', '.'));
        self::assertTrue(Str::isEmpty(''));
        self::assertTrue(Str::isEmpty(null));
        self::assertFalse(Str::isEmpty('x'));
    }
}
