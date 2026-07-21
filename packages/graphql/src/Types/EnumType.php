<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Types;

/**
 * A GraphQL enum type.
 */
final class EnumType
{
    /**
     * @param list<EnumValue> $values
     */
    public function __construct(
        public readonly string $name,
        public readonly array $values,
        public readonly string $description = '',
    ) {
    }

    public function kind(): TypeKind
    {
        return TypeKind::Enum;
    }

    public function has(string $name): bool
    {
        foreach ($this->values as $value) {
            if ($value->name === $name) {
                return true;
            }
        }

        return false;
    }
}
