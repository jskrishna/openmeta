<?php

declare(strict_types=1);

namespace OpenMeta\Security\Permissions;

use OpenMeta\Security\Contracts\CapabilityCheckerInterface;
use OpenMeta\Security\Contracts\GateInterface;
use OpenMeta\Security\Exceptions\AuthorizationException;

/**
 * Single “can the current identity perform X?” entry for OpenMeta permissions.
 * Fail closed on unknown permissions. Storage-independent via CapabilityCheckerInterface.
 */
final class Gate implements GateInterface
{
    /** @var array<string, bool> */
    private array $cache = [];

    public function __construct(
        private readonly PermissionMap $map,
        private readonly CapabilityCheckerInterface $capabilities,
    ) {
    }

    public function can(string $permission): bool
    {
        if (array_key_exists($permission, $this->cache)) {
            return $this->cache[$permission];
        }

        if (! $this->map->has($permission)) {
            return $this->cache[$permission] = false;
        }

        foreach ($this->map->capabilitiesFor($permission) as $capability) {
            if ($this->capabilities->can($capability)) {
                return $this->cache[$permission] = true;
            }
        }

        return $this->cache[$permission] = false;
    }

    public function cannot(string $permission): bool
    {
        return ! $this->can($permission);
    }

    /**
     * @throws AuthorizationException
     */
    public function authorize(string $permission): void
    {
        if ($this->cannot($permission)) {
            throw new AuthorizationException(
                sprintf('Unauthorized for permission [%s].', $permission)
            );
        }
    }

    public function flushCache(): void
    {
        $this->cache = [];
    }
}
