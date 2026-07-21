<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Traits\Conditionable;

final class ConditionableTest extends SupportTestCase
{
    public function test_when_and_unless(): void
    {
        $subject = new class {
            use Conditionable;

            public string $flag = 'base';
        };

        $subject->when(true, static function (object $s): void {
            $s->flag = 'when';
        });
        self::assertSame('when', $subject->flag);

        $subject->unless(false, static function (object $s): void {
            $s->flag = 'unless';
        });
        self::assertSame('unless', $subject->flag);

        $result = $subject->when(false, static fn (): string => 'no', static fn (): string => 'default');
        self::assertSame('default', $result);
    }
}
