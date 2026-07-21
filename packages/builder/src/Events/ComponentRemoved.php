<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Events;

final class ComponentRemoved
{
    public function __construct(public readonly string $nodeId)
    {
    }
}
