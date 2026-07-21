<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests;

use OpenMeta\Security\Sanitization\Sanitizer;

final class SanitizerTest extends SecurityTestCase
{
    public function test_text_email_int_bool_url_key(): void
    {
        self::assertSame('Hello world', Sanitizer::text("Hello <b>world</b>\n"));
        self::assertSame('a@b.com', Sanitizer::email('a@b.com'));
        self::assertSame(42, Sanitizer::int('42'));
        self::assertSame(0, Sanitizer::int('nope'));
        self::assertTrue(Sanitizer::bool('true'));
        self::assertFalse(Sanitizer::bool('false'));
        self::assertStringContainsString('https://example.com', Sanitizer::url('https://example.com/path'));
        self::assertSame('field_name', Sanitizer::key('Field Name!'));
    }

    public function test_array_sanitizer(): void
    {
        $result = Sanitizer::array(
            ['Name' => '<b>x</b>', 'Age' => '9'],
            static fn (mixed $v): string => Sanitizer::text($v)
        );

        self::assertSame('x', $result['name']);
        self::assertSame('9', $result['age']);
    }

    public function test_nested_sanitizer(): void
    {
        $result = Sanitizer::nested([
            'user' => [
                'name' => '<b>Ada</b>',
                'meta' => (object) ['city' => '<i>NY</i>'],
            ],
        ]);

        self::assertSame('Ada', $result['user']['name']);
        self::assertSame('NY', $result['user']['meta']['city']);
    }
}
