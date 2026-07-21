<?php

declare(strict_types=1);

namespace OpenMeta\Database\Metadata;

/**
 * Column metadata for schema introspection / future Fields packaging.
 */
final class ColumnDefinition
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly ?int $length = null,
        public readonly bool $nullable = false,
        public readonly bool $primary = false,
        public readonly mixed $default = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'length' => $this->length,
            'nullable' => $this->nullable,
            'primary' => $this->primary,
            'default' => $this->default,
        ];
    }
}
