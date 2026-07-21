<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Capabilities;

use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Seeds OpenMeta permissions onto WordPress roles.
 */
final class CapabilityRegistrar
{
    public function __construct(
        private readonly WordPressRuntimeInterface $wp,
        private readonly PermissionMap $map = new PermissionMap(),
    ) {
    }

    /**
     * @param list<string> $roles
     */
    public function register(array $roles = ['administrator']): void
    {
        foreach (Permission::all() as $permission) {
            foreach ($roles as $role) {
                $this->wp->addCapability($role, $permission);
            }

            foreach ($this->map->capabilitiesFor($permission) as $wpCap) {
                foreach ($roles as $role) {
                    $this->wp->addCapability($role, $wpCap);
                }
            }
        }
    }

    /** @return list<string> */
    public function permissions(): array
    {
        return Permission::all();
    }
}
