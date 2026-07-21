<?php

declare(strict_types=1);

namespace OpenMeta\Database\Events;

final class QueryExecuted
{
    /**
     * @param array<int|string, mixed> $bindings
     */
    public function __construct(
        public readonly string $sql,
        public readonly array $bindings = [],
        public readonly float $timeMs = 0.0,
    ) {
    }
}
