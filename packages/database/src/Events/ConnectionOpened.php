<?php

declare(strict_types=1);

namespace OpenMeta\Database\Events;

final class ConnectionOpened
{
    public function __construct(public readonly string $name, public readonly string $driver)
    {
    }
}
