<?php

declare(strict_types=1);

namespace OpenMeta\Database\Events;

final class MigrationStarted
{
    public function __construct(public readonly string $migration)
    {
    }
}
