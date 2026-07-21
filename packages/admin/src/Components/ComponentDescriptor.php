<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Components;

/**
 * Structured UI payload for {@see \OpenMeta\Ui} renderers — not raw HTML in admin.
 */
final class ComponentDescriptor
{
    /**
     * @param array<string, mixed> $props
     */
    public function __construct(
        public readonly string $type,
        public readonly array $props = [],
    ) {
    }

    /**
     * @return array{type: string, props: array<string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'props' => $this->props,
        ];
    }
}
