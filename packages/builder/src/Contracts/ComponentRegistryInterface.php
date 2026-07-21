<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Contracts;

use OpenMeta\Builder\Registry\ComponentDescriptor;

/**
 * Registry for canvas component types (field cards, sections, chrome).
 */
interface ComponentRegistryInterface
{
    public function register(ComponentDescriptor $descriptor): void;

    public function has(string $type): bool;

    public function get(string $type): ComponentDescriptor;

    /** @return list<ComponentDescriptor> */
    public function all(): array;

    /** @return list<ComponentDescriptor> */
    public function inCategory(string $category): array;

    /** @return list<string> */
    public function categories(): array;
}
