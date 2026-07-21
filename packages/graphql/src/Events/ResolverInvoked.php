<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Events;

/**
 * Dispatched immediately before a resolver runs.
 */
final class ResolverInvoked
{
    public function __construct(
        public readonly string $field,
        public readonly string $resolver,
    ) {
    }
}
