<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Unions;

use OpenMeta\GraphQL\Types\TypeKind;

/**
 * A GraphQL union type — one of several object types.
 */
final class UnionType
{
    /**
     * @param list<string> $members member object type names
     */
    public function __construct(
        public readonly string $name,
        public readonly array $members,
        public readonly string $description = '',
    ) {
    }

    public function kind(): TypeKind
    {
        return TypeKind::Union;
    }
}
