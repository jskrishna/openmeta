<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests\Security;

use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Escaping\Escaper;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Security\Tests\SecurityTestCase;

final class FailClosedAuthzTest extends SecurityTestCase
{
    public function test_unauthorized_cannot_manage_and_output_is_escaped(): void
    {
        $gate = new Gate(new PermissionMap(), new ArrayCapabilityChecker());
        $this->assertTrue($gate->cannot(Permission::MANAGE_FIELDS));
        $this->assertStringNotContainsString('<script>', Escaper::html('<script>alert(1)</script>'));
    }
}
