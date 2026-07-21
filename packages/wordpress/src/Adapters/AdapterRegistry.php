<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Adapters;

/**
 * Named adapter registry for WordPress bridge services.
 */
final class AdapterRegistry
{
    /** @var array<string, object> */
    private array $adapters = [];

    public function register(string $name, object $adapter): void
    {
        $this->adapters[$name] = $adapter;
    }

    public function has(string $name): bool
    {
        return isset($this->adapters[$name]);
    }

    public function get(string $name): object
    {
        if (! isset($this->adapters[$name])) {
            throw new \InvalidArgumentException(sprintf('Adapter [%s] is not registered.', $name));
        }

        return $this->adapters[$name];
    }

    /** @return list<string> */
    public function names(): array
    {
        return array_keys($this->adapters);
    }
}
