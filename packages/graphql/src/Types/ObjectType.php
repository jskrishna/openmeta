<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Types;

/**
 * A GraphQL object type — a named set of fields.
 */
final class ObjectType
{
    /**
     * @param list<FieldDefinition> $fields
     * @param list<string>          $interfaces implemented interface names
     */
    public function __construct(
        public readonly string $name,
        public readonly array $fields,
        public readonly string $description = '',
        public readonly array $interfaces = [],
    ) {
    }

    public function kind(): TypeKind
    {
        return TypeKind::Object;
    }

    /**
     * @return list<string>
     */
    public function fieldNames(): array
    {
        return array_map(static fn (FieldDefinition $field): string => $field->name, $this->fields);
    }

    public function field(string $name): ?FieldDefinition
    {
        foreach ($this->fields as $field) {
            if ($field->name === $name) {
                return $field;
            }
        }

        return null;
    }
}
