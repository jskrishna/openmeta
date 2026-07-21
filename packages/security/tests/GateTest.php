<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests;

use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Exceptions\AuthorizationException;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\Permissions\PermissionMap;

final class GateTest extends SecurityTestCase
{
    public function test_can_allows_when_mapped_capability_granted(): void
    {
        $caps = new ArrayCapabilityChecker(['manage_options']);
        $gate = new Gate(new PermissionMap(), $caps);

        self::assertTrue($gate->can(Permission::MANAGE_FIELDS));
        self::assertFalse($gate->can(Permission::EDIT_CONTENT));
        self::assertFalse($gate->can('openmeta.unknown'));
    }

    public function test_authorize_throws_when_denied(): void
    {
        $gate = new Gate(new PermissionMap(), new ArrayCapabilityChecker());

        $this->expectException(AuthorizationException::class);
        $gate->authorize(Permission::MANAGE);
    }

    public function test_custom_permission_map(): void
    {
        $map = new PermissionMap();
        $map->define('openmeta.custom', ['custom_cap']);
        $caps = new ArrayCapabilityChecker(['custom_cap']);
        $gate = new Gate($map, $caps);

        self::assertTrue($gate->can('openmeta.custom'));
    }

    public function test_permission_cache_can_be_flushed(): void
    {
        $caps = new ArrayCapabilityChecker();
        $gate = new Gate(new PermissionMap(), $caps);

        self::assertFalse($gate->can(Permission::READ));
        $caps->grant(['read']);
        self::assertFalse($gate->can(Permission::READ));
        $gate->flushCache();
        self::assertTrue($gate->can(Permission::READ));
    }
}
