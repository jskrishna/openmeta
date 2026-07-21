<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Events;

final class SchemaSaved
{
    /**
     * @param array<string, mixed> $schema
     */
    public function __construct(public readonly array $schema)
    {
    }
}
