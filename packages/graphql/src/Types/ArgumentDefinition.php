<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Types;

/**
 * A field / operation argument.
 */
final class ArgumentDefinition
{
    public function __construct(
        public readonly string $name,
        public readonly TypeReference $type,
        public readonly string $description = '',
        public readonly mixed $defaultValue = null,
        public readonly bool $hasDefault = false,
    ) {
    }

    public function toSdl(): string
    {
        return $this->name . ': ' . $this->type->toSdl();
    }
}
