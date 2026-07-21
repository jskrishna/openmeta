<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Contracts;

use OpenMeta\Admin\Components\ComponentDescriptor;

/**
 * Admin component that returns a UI descriptor (no domain logic).
 */
interface RenderableComponentInterface
{
    /**
     * @param array<string, mixed> $props
     */
    public function render(array $props = []): ComponentDescriptor;
}
