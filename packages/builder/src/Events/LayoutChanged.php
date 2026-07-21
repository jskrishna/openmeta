<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Events;

final class LayoutChanged
{
    /**
     * @param array<string, mixed> $layout
     */
    public function __construct(public readonly array $layout)
    {
    }
}
