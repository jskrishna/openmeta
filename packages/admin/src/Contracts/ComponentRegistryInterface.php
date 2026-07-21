<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Contracts;

use OpenMeta\Admin\Components\ComponentDescriptor;

/**
 * Named admin UI components (descriptors, not business logic).
 */
interface ComponentRegistryInterface
{
    public function register(string $id, RenderableComponentInterface $component): void;

    public function render(string $id, array $props = []): ComponentDescriptor;
}
