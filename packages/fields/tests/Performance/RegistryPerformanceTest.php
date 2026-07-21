<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests\Performance;

use OpenMeta\Fields\Tests\FieldsTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class RegistryPerformanceTest extends FieldsTestCase
{
    use AssertsPerformanceBudget;

    public function test_registry_lookups_under_budget(): void
    {
        $this->assertUnderMs(100.0, function (): void {
            for ($i = 0; $i < 1000; $i++) {
                $this->fields->make('text', 'f' . $i);
            }
        }, 'fields registry');
    }
}
