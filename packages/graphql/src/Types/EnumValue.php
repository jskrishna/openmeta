<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Types;

/**
 * A single value of an enum type.
 */
final class EnumValue
{
    public function __construct(
        public readonly string $name,
        public readonly mixed $value = null,
        public readonly string $description = '',
        public readonly ?string $deprecationReason = null,
    ) {
    }

    public function resolvedValue(): mixed
    {
        return $this->value ?? $this->name;
    }
}
