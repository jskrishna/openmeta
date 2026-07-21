<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Components;

use OpenMeta\Admin\Contracts\ComponentRegistryInterface;
use OpenMeta\Admin\Contracts\RenderableComponentInterface;
use OpenMeta\Admin\Exceptions\AdminException;

/**
 * Extensible named component registry.
 */
final class ComponentRegistry implements ComponentRegistryInterface
{
    /** @var array<string, RenderableComponentInterface> */
    private array $components = [];

    public function register(string $id, RenderableComponentInterface $component): void
    {
        $this->components[$id] = $component;
    }

    public function has(string $id): bool
    {
        return isset($this->components[$id]);
    }

    public function render(string $id, array $props = []): ComponentDescriptor
    {
        if (! isset($this->components[$id])) {
            throw new AdminException(sprintf('Unknown admin component [%s].', $id));
        }

        return $this->components[$id]->render($props);
    }
}
