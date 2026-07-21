<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Errors\GraphQLAuthorizationException;
use OpenMeta\GraphQL\Resolvers\ResolutionContext;

/**
 * Authorizes access to a field / operation.
 *
 * Reuses the Security package's permission gate — it never re-implements
 * permission logic.
 */
interface FieldAuthorizerInterface
{
    /**
     * @throws GraphQLAuthorizationException When the permission is denied
     */
    public function authorize(?string $permission, ResolutionContext $context): void;
}
