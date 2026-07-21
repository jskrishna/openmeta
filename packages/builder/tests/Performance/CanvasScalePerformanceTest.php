<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Performance;

use OpenMeta\Builder\Tests\BuilderTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class CanvasScalePerformanceTest extends BuilderTestCase
{
    use AssertsPerformanceBudget;

    public function test_many_fields_under_budget(): void
    {
        $this->assertUnderMs(500.0, function (): void {
            for ($i = 0; $i < 100; $i++) {
                $this->dragDrop->dropNew($this->canvas, 'text', 'f' . $i);
            }
        }, 'builder canvas scale');
    }
}
