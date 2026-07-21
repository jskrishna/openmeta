<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Helpers\Helpers;

final class HelpersTest extends SupportTestCase
{
    public function test_value_tap_with_blank_filled_basename(): void
    {
        self::assertSame(5, Helpers::value(static fn (): int => 5));
        self::assertSame('x', Helpers::value('x'));

        $seen = null;
        $tapped = Helpers::tap('ok', static function (string $v) use (&$seen): void {
            $seen = $v;
        });
        self::assertSame('ok', $tapped);
        self::assertSame('ok', $seen);

        self::assertSame(3, Helpers::with(2, static fn (int $n): int => $n + 1));
        self::assertTrue(Helpers::blank(''));
        self::assertTrue(Helpers::blank([]));
        self::assertTrue(Helpers::filled('a'));
        self::assertSame('HelpersTest', Helpers::classBasename($this));
    }
}
