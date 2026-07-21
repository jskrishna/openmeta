<?php

declare(strict_types=1);

namespace OpenMeta\Database\Schema;

/**
 * Declarative table definition. Migration owns DDL execution.
 */
final class Blueprint
{
    /** @var list<array{
     *     name: string,
     *     type: string,
     *     length: int|null,
     *     nullable: bool,
     *     primary: bool,
     *     autoIncrement: bool,
     *     default: mixed
     * }> */
    private array $columns = [];

    /** @var list<string> */
    private array $indexes = [];

    /** @var list<string> */
    private array $uniques = [];

    /** @var list<array{column: string, references: string, on: string|null}> */
    private array $foreignKeys = [];

    public function __construct(private readonly string $table)
    {
    }

    public function table(): string
    {
        return $this->table;
    }

    public function id(string $name = 'id'): self
    {
        $this->columns[] = [
            'name' => $name,
            'type' => 'id',
            'length' => null,
            'nullable' => false,
            'primary' => true,
            'autoIncrement' => true,
            'default' => null,
        ];

        return $this;
    }

    public function string(string $name, int $length = 255, bool $nullable = false): self
    {
        return $this->addColumn($name, 'string', $length, $nullable);
    }

    public function text(string $name, bool $nullable = false): self
    {
        return $this->addColumn($name, 'text', null, $nullable);
    }

    public function integer(string $name, bool $nullable = false): self
    {
        return $this->addColumn($name, 'integer', null, $nullable);
    }

    public function boolean(string $name, bool $nullable = false): self
    {
        return $this->addColumn($name, 'boolean', null, $nullable);
    }

    public function timestamps(): self
    {
        $this->string('created_at', 32, true);
        $this->string('updated_at', 32, true);

        return $this;
    }

    public function index(string $column): self
    {
        $this->indexes[] = $column;

        return $this;
    }

    public function unique(string $column): self
    {
        $this->uniques[] = $column;

        return $this;
    }

    /**
     * Declarative FK metadata for driver grammars (compiled later per driver).
     */
    public function foreignId(string $column, string $references = 'id', ?string $on = null): self
    {
        $this->addColumn($column, 'integer', null, false);
        $this->foreignKeys[] = [
            'column' => $column,
            'references' => $references,
            'on' => $on,
        ];

        return $this;
    }

    /**
     * @return list<array{
     *     name: string,
     *     type: string,
     *     length: int|null,
     *     nullable: bool,
     *     primary: bool,
     *     autoIncrement: bool,
     *     default: mixed
     * }>
     */
    public function columns(): array
    {
        return $this->columns;
    }

    /**
     * @return list<string>
     */
    public function indexes(): array
    {
        return $this->indexes;
    }

    /**
     * @return list<string>
     */
    public function uniques(): array
    {
        return $this->uniques;
    }

    /**
     * @return list<array{column: string, references: string, on: string|null}>
     */
    public function foreignKeys(): array
    {
        return $this->foreignKeys;
    }

    private function addColumn(string $name, string $type, ?int $length, bool $nullable): self
    {
        $this->columns[] = [
            'name' => $name,
            'type' => $type,
            'length' => $length,
            'nullable' => $nullable,
            'primary' => false,
            'autoIncrement' => false,
            'default' => null,
        ];

        return $this;
    }
}
