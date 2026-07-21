<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Interfaces;

use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\TypeKind;

/**
 * A GraphQL interface type — a shared set of fields object types may implement.
 */
final class InterfaceType
{
    /**
     * @param list<FieldDefinition> $fields
     */
    public function __construct(
        public readonly string $name,
        public readonly array $fields,
        public readonly string $description = '',
    ) {
    }

    public function kind(): TypeKind
    {
        return TypeKind::Interface;
    }

    /**
     * @return list<string>
     */
    public function fieldNames(): array
    {
        return array_map(static fn (FieldDefinition $field): string => $field->name, $this->fields);
    }
}
