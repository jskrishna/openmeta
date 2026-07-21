<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Resolvers\ResolutionContext;

/**
 * Resolves the value of a field / operation.
 *
 * Resolvers consume repositories and services (injected via the container).
 * They MUST NOT access storage directly.
 */
interface ResolverInterface
{
    /**
     * @param mixed                $root Parent value (null for root operations)
     * @param array<string, mixed> $args Field arguments
     */
    public function resolve(mixed $root, array $args, ResolutionContext $context): mixed;
}
