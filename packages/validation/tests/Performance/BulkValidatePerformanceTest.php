<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\Performance;

use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;
use OpenMeta\Validation\Tests\ValidationTestCase;
use OpenMeta\Validation\Validation;

final class BulkValidatePerformanceTest extends ValidationTestCase
{
    use AssertsPerformanceBudget;

    public function test_bulk_validate_under_budget(): void
    {
        $this->assertUnderMs(200.0, static function (): void {
            for ($i = 0; $i < 200; $i++) {
                Validation::make(['n' => $i], ['n' => 'required|integer'])->passes();
            }
        }, 'validation bulk');
    }
}
