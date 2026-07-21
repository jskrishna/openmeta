<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Inputs;

use OpenMeta\GraphQL\Types\TypeKind;

/**
 * A GraphQL input object type.
 */
final class InputType
{
    /**
     * @param list<InputField> $fields
     */
    public function __construct(
        public readonly string $name,
        public readonly array $fields,
        public readonly string $description = '',
    ) {
    }

    public function kind(): TypeKind
    {
        return TypeKind::InputObject;
    }

    /**
     * Validation rules for all fields, keyed by field name.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        $rules = [];

        foreach ($this->fields as $field) {
            if ($field->rules !== []) {
                $rules[$field->name] = $field->rules;
            }
        }

        return $rules;
    }
}
