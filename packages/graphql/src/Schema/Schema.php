<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Schema;

use OpenMeta\GraphQL\Types\ObjectType;

/**
 * An assembled, immutable schema snapshot: the root operation types plus the
 * registries the types were drawn from.
 */
final class Schema
{
    public function __construct(
        public readonly ObjectType $queryType,
        public readonly ?ObjectType $mutationType,
        public readonly SchemaRegistries $registries,
        public readonly ?string $subscriptionTypeName = null,
    ) {
    }

    public function hasMutations(): bool
    {
        return $this->mutationType !== null;
    }
}
