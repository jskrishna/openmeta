<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Exceptions\UuidException;
use OpenMeta\Support\Uuid\Uuid;
use OpenMeta\Support\ValueObjects\UuidValue;

final class UuidValueTest extends SupportTestCase
{
    public function test_from_and_equals(): void
    {
        $raw = Uuid::v4();
        $value = UuidValue::from($raw);

        self::assertSame(strtolower($raw), $value->toString());
        self::assertTrue($value->equals(Uuid::parse($raw)));
        self::assertSame(strtolower($raw), (string) $value);
    }

    public function test_v4_and_nil(): void
    {
        self::assertTrue(Uuid::isValid(UuidValue::v4()->toString()));
        self::assertSame(Uuid::nil(), UuidValue::nil()->toString());
    }

    public function test_invalid_throws(): void
    {
        $this->expectException(UuidException::class);
        UuidValue::from('not-a-uuid');
    }
}
