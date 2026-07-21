<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Authorization;

use OpenMeta\Rest\Exceptions\AuthorizationException;
use OpenMeta\Security\Permissions\Gate;

/**
 * Thin wrapper over {@see Gate} — any matching permission grants access.
 */
final class GateAuthorizer
{
    public function __construct(private readonly Gate $gate)
    {
    }

    /**
     * @param list<string> $permissions
     *
     * @throws AuthorizationException
     */
    public function authorize(array $permissions): void
    {
        if ($permissions === []) {
            return;
        }

        foreach ($permissions as $permission) {
            if ($this->gate->can($permission)) {
                return;
            }
        }

        throw new AuthorizationException();
    }
}
