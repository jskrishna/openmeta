<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Registry;

/**
 * Registered canvas component metadata (no UI rendering).
 */
final class ComponentDescriptor
{
    /**
     * @param list<string> $tags
     * @param callable(): array<string, mixed>|null $lazyFactory
     */
    public function __construct(
        public readonly string $type,
        public readonly string $label,
        public readonly string $category,
        public readonly string $version = '1.0.0',
        public readonly array $tags = [],
        private mixed $lazyFactory = null,
    ) {
    }

    /** @return array<string, mixed>|null */
    public function resolve(): ?array
    {
        if (! is_callable($this->lazyFactory)) {
            return null;
        }

        $result = ($this->lazyFactory)();

        return is_array($result) ? $result : null;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'label' => $this->label,
            'category' => $this->category,
            'version' => $this->version,
            'tags' => $this->tags,
        ];
    }
}
