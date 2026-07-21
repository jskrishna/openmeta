<?php

declare(strict_types=1);

namespace OpenMeta\Database\Connections;

use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Exceptions\InvalidDriverException;
use PDO;

/**
 * Builds connections from config arrays (memory / sqlite / mysql PDO).
 * `wordpress` driver uses MemoryConnection until `@openmeta/wordpress` binds a wpdb adapter.
 */
final class ConnectionFactory
{
    /**
     * @param array<string, mixed> $config
     */
    public function make(array $config): ConnectionInterface
    {
        $driver = (string) ($config['driver'] ?? 'memory');
        $prefix = (string) ($config['prefix'] ?? 'om_');

        return match ($driver) {
            'memory', 'wordpress' => new MemoryConnection($prefix),
            'sqlite' => $this->sqlite($config, $prefix),
            'mysql', 'mariadb', 'pgsql' => PdoConnection::fromConfig($config),
            default => throw new InvalidDriverException(sprintf('Unsupported driver [%s].', $driver)),
        };
    }

    /**
     * @param array<string, mixed> $config
     */
    private function sqlite(array $config, string $prefix): ConnectionInterface
    {
        if (! in_array('sqlite', PDO::getAvailableDrivers(), true)) {
            return new MemoryConnection($prefix);
        }

        return PdoConnection::fromConfig($config);
    }
}
