<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Interfaces;

use OpenMeta\GraphQL\Errors\DuplicateTypeException;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;

/**
 * Registry of interface types.
 */
final class InterfaceRegistry
{
    /** @var array<string, InterfaceType> */
    private array $interfaces = [];

    public function register(InterfaceType $interface): void
    {
        if (isset($this->interfaces[$interface->name])) {
            throw DuplicateTypeException::named('Interface', $interface->name);
        }

        $this->interfaces[$interface->name] = $interface;
    }

    public function has(string $name): bool
    {
        return isset($this->interfaces[$name]);
    }

    public function get(string $name): InterfaceType
    {
        return $this->interfaces[$name] ?? throw TypeNotFoundException::named($name);
    }

    /**
     * @return list<InterfaceType>
     */
    public function all(): array
    {
        return array_values($this->interfaces);
    }
}
