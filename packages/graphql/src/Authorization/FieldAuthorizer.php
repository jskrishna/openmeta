<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Authorization;

use OpenMeta\GraphQL\Contracts\FieldAuthorizerInterface;
use OpenMeta\GraphQL\Errors\GraphQLAuthorizationException;
use OpenMeta\GraphQL\Resolvers\ResolutionContext;
use OpenMeta\Security\Contracts\GateInterface;

/**
 * Authorizes fields through the Security package's permission gate.
 *
 * A null permission means the field is public. This class holds no permission
 * logic of its own — it only asks the {@see GateInterface}.
 */
final class FieldAuthorizer implements FieldAuthorizerInterface
{
    public function __construct(private readonly GateInterface $gate)
    {
    }

    public function authorize(?string $permission, ResolutionContext $context): void
    {
        if ($permission === null) {
            return;
        }

        if ($this->gate->cannot($permission)) {
            throw GraphQLAuthorizationException::forPermission($permission);
        }
    }
}
