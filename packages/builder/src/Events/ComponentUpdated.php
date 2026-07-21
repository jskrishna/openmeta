<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Events;

use OpenMeta\Builder\Canvas\CanvasNode;

final class ComponentUpdated
{
    public function __construct(
        public readonly CanvasNode $before,
        public readonly CanvasNode $after,
    ) {
    }
}
