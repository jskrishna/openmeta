<?php

declare(strict_types=1);

namespace OpenMeta\Database\Contracts;

/**
 * Driver capability / factory contract for future MySQL, SQLite, WP adapters.
 */
interface DriverInterface
{
    public function name(): string;

    /**
     * @param array<string, mixed> $config
     */
    public function connect(array $config): ConnectionInterface;
}
