<?php

declare(strict_types=1);

namespace OpenMeta\Database\Metadata;

use OpenMeta\Database\Schema\Blueprint;

/**
 * Builds table/column metadata from Blueprint definitions.
 */
final class SchemaInspector
{
    public function fromBlueprint(Blueprint $blueprint): TableDefinition
    {
        $columns = [];

        foreach ($blueprint->columns() as $column) {
            $columns[] = new ColumnDefinition(
                $column['name'],
                $column['type'],
                $column['length'],
                $column['nullable'],
                $column['primary'],
                $column['default'],
            );
        }

        return new TableDefinition($blueprint->table(), $columns, $blueprint->indexes());
    }

    /**
     * @return array<string, bool>
     */
    public function driverCapabilities(string $driver): array
    {
        return match ($driver) {
            'memory' => ['transactions' => false, 'joins' => false, 'json' => false],
            'sqlite' => ['transactions' => true, 'joins' => true, 'json' => true],
            'mysql', 'mariadb' => ['transactions' => true, 'joins' => true, 'json' => true],
            'pgsql' => ['transactions' => true, 'joins' => true, 'json' => true],
            default => ['transactions' => false, 'joins' => false, 'json' => false],
        };
    }
}
