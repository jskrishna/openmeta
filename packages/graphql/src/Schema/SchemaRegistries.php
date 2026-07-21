<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Schema;

use OpenMeta\GraphQL\Directives\DirectiveRegistry;
use OpenMeta\GraphQL\Inputs\InputRegistry;
use OpenMeta\GraphQL\Interfaces\InterfaceRegistry;
use OpenMeta\GraphQL\Mutations\MutationRegistry;
use OpenMeta\GraphQL\Queries\QueryRegistry;
use OpenMeta\GraphQL\Resolvers\ResolverRegistry;
use OpenMeta\GraphQL\Scalars\ScalarRegistry;
use OpenMeta\GraphQL\Types\TypeRegistry;
use OpenMeta\GraphQL\Unions\UnionRegistry;

/**
 * The shared set of registries the schema is assembled from.
 *
 * This is the single object handed to {@see \OpenMeta\GraphQL\Contracts\SchemaExtensionInterface}
 * so extensions can contribute types, queries, mutations, scalars, and directives.
 */
final class SchemaRegistries
{
    public function __construct(
        public readonly TypeRegistry $types,
        public readonly ScalarRegistry $scalars,
        public readonly InputRegistry $inputs,
        public readonly InterfaceRegistry $interfaces,
        public readonly UnionRegistry $unions,
        public readonly DirectiveRegistry $directives,
        public readonly QueryRegistry $queries,
        public readonly MutationRegistry $mutations,
        public readonly ResolverRegistry $resolvers,
    ) {
    }

    /**
     * Whether a name resolves to any registered type (scalar, object, enum,
     * input, interface, or union).
     */
    public function isKnownType(string $name): bool
    {
        return $this->scalars->has($name)
            || $this->types->has($name)
            || $this->inputs->has($name)
            || $this->interfaces->has($name)
            || $this->unions->has($name);
    }
}
