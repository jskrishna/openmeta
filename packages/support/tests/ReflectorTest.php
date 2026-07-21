<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Reflection\Reflector;
use OpenMeta\Support\Traits\Conditionable;

final class ReflectorTest extends SupportTestCase
{
    protected function tearDown(): void
    {
        Reflector::clearCache();
        parent::tearDown();
    }

    public function test_short_name_and_method(): void
    {
        self::assertSame('ReflectorTest', Reflector::shortName($this));
        self::assertSame(
            'test_short_name_and_method',
            Reflector::method($this, 'test_short_name_and_method')->getName()
        );
    }

    public function test_class_uses_recursive(): void
    {
        $subject = new class {
            use Conditionable;
        };

        $traits = Reflector::classUsesRecursive($subject);
        self::assertContains(Conditionable::class, $traits);
    }
}
