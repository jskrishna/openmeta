<?php

declare(strict_types=1);

namespace OpenMeta\Fields\GraphQL;

use OpenMeta\Fields\Field\Field;

/**
 * Field GraphQL type-map contracts. Server mount stays in `@openmeta/api`.
 */
final class FieldGraphQLTypeMap
{
    /**
     * @return array{name: string, type: string, graphql_type: string, nullable: bool}
     */
    public function map(Field $field): array
    {
        return [
            'name' => $field->name(),
            'type' => $field->type(),
            'graphql_type' => $this->graphqlType($field->type()),
            'nullable' => ! $field->isRequired(),
        ];
    }

    private function graphqlType(string $fieldType): string
    {
        return match ($fieldType) {
            'number', 'range' => 'Float',
            'boolean', 'checkbox', 'toggle' => 'Boolean',
            'repeater', 'relationship', 'gallery', 'multiselect', 'group', 'object' => 'JSON',
            default => 'String',
        };
    }
}
