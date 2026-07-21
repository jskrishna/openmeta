<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Uuid\Uuid;

final class UuidTest extends SupportTestCase
{
    public function test_v4_is_valid_and_unique(): void
    {
        $a = Uuid::v4();
        $b = Uuid::v4();

        self::assertTrue(Uuid::isValid($a));
        self::assertTrue(Uuid::isValid($b));
        self::assertNotSame($a, $b);
        self::assertSame(4, (int) $a[14]);
    }

    public function test_nil_and_invalid(): void
    {
        self::assertTrue(Uuid::isValid(Uuid::nil()));
        self::assertFalse(Uuid::isValid('not-a-uuid'));
    }
}
