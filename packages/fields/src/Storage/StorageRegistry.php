<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Storage;

use OpenMeta\Fields\Contracts\FieldStorageInterface;
use OpenMeta\Fields\Exceptions\InvalidFieldException;

/**
 * Pluggable storage adapter catalog (meta / tables / JSON / external).
 */
final class StorageRegistry
{
    /** @var array<string, FieldStorageInterface> */
    private array $adapters = [];

    public function register(string $name, FieldStorageInterface $adapter): void
    {
        $this->adapters[$name] = $adapter;
    }

    public function has(string $name): bool
    {
        return isset($this->adapters[$name]);
    }

    public function get(string $name): FieldStorageInterface
    {
        if (! isset($this->adapters[$name])) {
            throw new InvalidFieldException(sprintf('Unknown storage adapter [%s].', $name));
        }

        return $this->adapters[$name];
    }
}
