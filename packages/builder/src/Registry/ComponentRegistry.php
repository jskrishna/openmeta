<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Registry;

use OpenMeta\Builder\Contracts\ComponentRegistryInterface;
use OpenMeta\Builder\Exceptions\BuilderException;

/**
 * Component registry — categories, discovery, lazy metadata, versioning.
 */
final class ComponentRegistry implements ComponentRegistryInterface
{
    /** @var array<string, ComponentDescriptor> */
    private array $components = [];

    public function register(ComponentDescriptor $descriptor): void
    {
        $this->components[$descriptor->type] = $descriptor;
    }

    public function has(string $type): bool
    {
        return isset($this->components[$type]);
    }

    public function get(string $type): ComponentDescriptor
    {
        if (! isset($this->components[$type])) {
            throw new BuilderException(sprintf('Unknown component type [%s].', $type));
        }

        return $this->components[$type];
    }

    public function all(): array
    {
        return array_values($this->components);
    }

    public function inCategory(string $category): array
    {
        return array_values(array_filter(
            $this->components,
            static fn (ComponentDescriptor $descriptor): bool => $descriptor->category === $category
        ));
    }

    public function categories(): array
    {
        $categories = [];
        foreach ($this->components as $descriptor) {
            $categories[$descriptor->category] = true;
        }

        return array_keys($categories);
    }
}
