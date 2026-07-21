<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Support;

/**
 * Stable unique id helper for canvas nodes and layouts.
 */
final class IdGenerator
{
    public static function node(string $prefix = 'node'): string
    {
        return $prefix . '_' . bin2hex(random_bytes(8));
    }
}
