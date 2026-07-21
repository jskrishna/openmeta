<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Unions;

use OpenMeta\GraphQL\Errors\DuplicateTypeException;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;

/**
 * Registry of union types.
 */
final class UnionRegistry
{
    /** @var array<string, UnionType> */
    private array $unions = [];

    public function register(UnionType $union): void
    {
        if (isset($this->unions[$union->name])) {
            throw DuplicateTypeException::named('Union', $union->name);
        }

        $this->unions[$union->name] = $union;
    }

    public function has(string $name): bool
    {
        return isset($this->unions[$name]);
    }

    public function get(string $name): UnionType
    {
        return $this->unions[$name] ?? throw TypeNotFoundException::named($name);
    }

    /**
     * @return list<UnionType>
     */
    public function all(): array
    {
        return array_values($this->unions);
    }
}
