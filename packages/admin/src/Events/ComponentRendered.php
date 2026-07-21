<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Events;

use OpenMeta\Admin\Components\ComponentDescriptor;

final class ComponentRendered
{
    /**
     * @param array<string, mixed> $props
     */
    public function __construct(
        public readonly string $componentId,
        public readonly ComponentDescriptor $descriptor,
        public readonly array $props = [],
    ) {
    }
}
