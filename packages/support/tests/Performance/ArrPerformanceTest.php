<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests\Performance;

use OpenMeta\Support\Arr\Arr;
use OpenMeta\Support\Tests\SupportTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class ArrPerformanceTest extends SupportTestCase
{
    use AssertsPerformanceBudget;

    public function test_bulk_get_under_budget(): void
    {
        $row = ['x' => ['y' => 'z']];
        $this->assertUnderMs(50.0, static function () use ($row): void {
            $last = null;
            for ($i = 0; $i < 5000; $i++) {
                $last = Arr::get($row, 'x.y');
            }
            \PHPUnit\Framework\Assert::assertSame('z', $last);
        }, 'support Arr::get');
    }
}
