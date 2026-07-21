<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Types;

use OpenMeta\GraphQL\Contracts\TypeRegistryInterface;
use OpenMeta\GraphQL\Errors\DuplicateTypeException;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;

/**
 * Registry of object and enum types.
 */
final class TypeRegistry implements TypeRegistryInterface
{
    /** @var array<string, ObjectType> */
    private array $objects = [];

    /** @var array<string, EnumType> */
    private array $enums = [];

    public function register(ObjectType $type): void
    {
        if (isset($this->objects[$type->name])) {
            throw DuplicateTypeException::named('Object type', $type->name);
        }

        $this->objects[$type->name] = $type;
    }

    public function registerEnum(EnumType $type): void
    {
        if (isset($this->enums[$type->name])) {
            throw DuplicateTypeException::named('Enum type', $type->name);
        }

        $this->enums[$type->name] = $type;
    }

    public function has(string $name): bool
    {
        return isset($this->objects[$name]) || isset($this->enums[$name]);
    }

    public function get(string $name): ObjectType
    {
        return $this->objects[$name] ?? throw TypeNotFoundException::named($name);
    }

    public function all(): array
    {
        return array_values($this->objects);
    }

    public function enums(): array
    {
        return array_values($this->enums);
    }
}
