<?php

declare(strict_types=1);

namespace OpenMeta\Database\Connections;

use OpenMeta\Database\Contracts\ConnectionInterface;

/**
 * Resolves the active connection for consumers.
 */
final class ConnectionManager
{
    public function __construct(
        private readonly ConnectionRegistry $registry,
        private readonly ConnectionFactory $factory,
    ) {
    }

    public function connection(?string $name = null): ConnectionInterface
    {
        return $this->registry->get($name);
    }

    /**
     * @param array<string, mixed> $config
     */
    public function add(string $name, array $config): ConnectionInterface
    {
        $connection = $this->factory->make($config);
        $this->registry->add($name, $connection);

        return $connection;
    }

    public function registry(): ConnectionRegistry
    {
        return $this->registry;
    }

    public function factory(): ConnectionFactory
    {
        return $this->factory;
    }
}
