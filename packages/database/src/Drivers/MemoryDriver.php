<?php

declare(strict_types=1);

namespace OpenMeta\Database\Drivers;

use OpenMeta\Database\Connections\MemoryConnection;
use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Contracts\DriverInterface;

/**
 * In-memory driver — default for CI / non-PDO environments.
 */
final class MemoryDriver implements DriverInterface
{
    public function name(): string
    {
        return 'memory';
    }

    public function connect(array $config): ConnectionInterface
    {
        $prefix = (string) ($config['prefix'] ?? 'om_');

        return new MemoryConnection($prefix);
    }
}
