<?php

declare(strict_types=1);

namespace OpenMeta\Api\Authz;

use OpenMeta\Api\Exceptions\AuthorizationException;
use OpenMeta\Api\Rest\Routes\Route;
use OpenMeta\Security\Permissions\Gate;

/**
 * Decides what the authenticated identity may do via `@openmeta/security` Gate.
 */
final class Authorizer
{
    public function __construct(private readonly Gate $gate)
    {
    }

    public function authorize(Route $route): void
    {
        $permissions = $route->permissions();

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
