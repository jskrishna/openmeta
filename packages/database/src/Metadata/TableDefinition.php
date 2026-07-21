<?php

declare(strict_types=1);

namespace OpenMeta\Database\Metadata;

/**
 * Table metadata snapshot.
 */
final class TableDefinition
{
    /**
     * @param list<ColumnDefinition> $columns
     * @param list<string> $indexes
     */
    public function __construct(
        public readonly string $name,
        public readonly array $columns = [],
        public readonly array $indexes = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'columns' => array_map(
                static fn (ColumnDefinition $c): array => $c->toArray(),
                $this->columns
            ),
            'indexes' => $this->indexes,
        ];
    }
}
