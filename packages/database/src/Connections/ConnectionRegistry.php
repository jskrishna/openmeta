<?php

declare(strict_types=1);

namespace OpenMeta\Database\Connections;

use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Exceptions\ConnectionException;

/**
 * Named connection registry.
 */
final class ConnectionRegistry
{
    /** @var array<string, ConnectionInterface> */
    private array $connections = [];

    private ?string $default = null;

    public function setDefault(string $name): void
    {
        $this->default = $name;
    }

    public function add(string $name, ConnectionInterface $connection): void
    {
        $this->connections[$name] = $connection;

        if ($this->default === null) {
            $this->default = $name;
        }
    }

    public function has(string $name): bool
    {
        return isset($this->connections[$name]);
    }

    public function get(?string $name = null): ConnectionInterface
    {
        $name ??= $this->default;

        if ($name === null || ! isset($this->connections[$name])) {
            throw new ConnectionException(
                sprintf('Database connection [%s] is not configured.', $name ?? 'null')
            );
        }

        return $this->connections[$name];
    }

    /**
     * @return list<string>
     */
    public function names(): array
    {
        return array_keys($this->connections);
    }
}
