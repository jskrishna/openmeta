<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Events;

/**
 * Dispatched after a root query executes (successfully or not).
 */
final class QueryExecuted
{
    /**
     * @param array<string, mixed> $args
     */
    public function __construct(
        public readonly string $name,
        public readonly array $args,
        public readonly bool $succeeded,
    ) {
    }
}
