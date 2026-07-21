<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Layouts;

use OpenMeta\Builder\Support\IdGenerator;

/**
 * Nested layout node with responsive metadata.
 */
final class LayoutNode
{
    /**
     * @param list<string> $children
     * @param array<string, mixed> $responsive
     */
    public function __construct(
        public readonly string $id,
        public readonly LayoutType $type,
        public readonly array $children = [],
        public readonly array $responsive = [],
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'children' => $this->children,
            'responsive' => $this->responsive,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $type = isset($data['type']) && is_string($data['type'])
            ? LayoutType::from($data['type'])
            : LayoutType::Section;

        $children = [];
        if (isset($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                if (is_string($child)) {
                    $children[] = $child;
                }
            }
        }

        return new self(
            (string) ($data['id'] ?? IdGenerator::node('layout')),
            $type,
            $children,
            is_array($data['responsive'] ?? null) ? $data['responsive'] : [],
        );
    }
}
