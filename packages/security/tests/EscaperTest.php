<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests;

use OpenMeta\Security\Escaping\Escaper;

final class EscaperTest extends SecurityTestCase
{
    public function test_html_attr_js_contexts(): void
    {
        self::assertSame('&lt;script&gt;', Escaper::html('<script>'));
        self::assertSame('&quot;x&quot;', Escaper::attr('"x"'));
        self::assertStringContainsString("\\'", Escaper::js("O'Reilly"));
        self::assertSame('a &amp; b', Escaper::textarea('a & b'));
        self::assertSame('safe-class', Escaper::css('safe-class!!!'));
        self::assertStringNotContainsString(';', Escaper::css('x;y'));
        self::assertSame('{"a":1}', Escaper::json(['a' => 1]));
    }
}
