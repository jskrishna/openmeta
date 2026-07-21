<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Directives;

use OpenMeta\GraphQL\Types\ArgumentDefinition;

/**
 * A GraphQL directive definition.
 */
final class DirectiveDefinition
{
    /**
     * @param list<string>             $locations valid directive locations
     * @param list<ArgumentDefinition> $args
     */
    public function __construct(
        public readonly string $name,
        public readonly array $locations,
        public readonly string $description = '',
        public readonly array $args = [],
    ) {
    }
}
