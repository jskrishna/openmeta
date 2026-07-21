<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests\Unit;

use OpenMeta\Support\Arr\Arr;
use OpenMeta\Support\Tests\SupportTestCase;

final class ArrGateTest extends SupportTestCase
{
    public function test_dot_get(): void
    {
        $this->assertSame(1, Arr::get(['a' => ['b' => 1]], 'a.b'));
    }
}
