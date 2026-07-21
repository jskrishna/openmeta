<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Errors\TypeNotFoundException;
use OpenMeta\GraphQL\Types\EnumType;
use OpenMeta\GraphQL\Types\ObjectType;

/**
 * Registry of object and enum types.
 */
interface TypeRegistryInterface
{
    public function register(ObjectType $type): void;

    public function registerEnum(EnumType $type): void;

    public function has(string $name): bool;

    /**
     * @throws TypeNotFoundException
     */
    public function get(string $name): ObjectType;

    /**
     * @return list<ObjectType>
     */
    public function all(): array;

    /**
     * @return list<EnumType>
     */
    public function enums(): array;
}
