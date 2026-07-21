<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Events;

use OpenMeta\Builder\Canvas\CanvasNode;

final class ComponentAdded
{
    public function __construct(public readonly CanvasNode $node)
    {
    }
}
