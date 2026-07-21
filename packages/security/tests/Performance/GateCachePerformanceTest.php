<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests\Performance;

use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Security\Tests\SecurityTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class GateCachePerformanceTest extends SecurityTestCase
{
    use AssertsPerformanceBudget;

    public function test_repeated_cap_checks_under_budget(): void
    {
        $caps = new ArrayCapabilityChecker(['manage_options']);
        $gate = new Gate(new PermissionMap(), $caps);

        $this->assertUnderMs(100.0, static function () use ($gate): void {
            for ($i = 0; $i < 1000; $i++) {
                $gate->can(Permission::MANAGE);
            }
        }, 'security gate checks');
    }
}
