<?php

declare(strict_types=1);

namespace OpenMeta\Database\Support;

/**
 * Safe SQL identifier quoting helpers (never for values).
 */
final class Identifier
{
    public static function quote(string $name, string $driver): string
    {
        if ($driver === 'sqlite' || $driver === 'pgsql') {
            return '"' . str_replace('"', '""', $name) . '"';
        }

        return '`' . str_replace('`', '``', $name) . '`';
    }
}
