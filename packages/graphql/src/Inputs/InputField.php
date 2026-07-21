<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Inputs;

use OpenMeta\GraphQL\Types\TypeReference;

/**
 * A field of an input object type.
 */
final class InputField
{
    /**
     * @param list<string> $rules validation rules applied to this field
     */
    public function __construct(
        public readonly string $name,
        public readonly TypeReference $type,
        public readonly string $description = '',
        public readonly mixed $defaultValue = null,
        public readonly bool $hasDefault = false,
        public readonly array $rules = [],
    ) {
    }

    public function toSdl(): string
    {
        return $this->name . ': ' . $this->type->toSdl();
    }
}
