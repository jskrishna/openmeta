<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests\Unit;

use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Security\Tests\SecurityTestCase;

final class GateUnitTest extends SecurityTestCase
{
    public function test_gate_denies_by_default(): void
    {
        $gate = new Gate(new PermissionMap(), new ArrayCapabilityChecker());
        $this->assertTrue($gate->cannot(Permission::MANAGE));
    }
}
