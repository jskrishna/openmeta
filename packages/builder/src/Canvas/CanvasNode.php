<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Canvas;

use OpenMeta\Builder\Support\IdGenerator;

/**
 * One canvas node (field, section, layout slot) in the builder schema.
 */
final class CanvasNode
{
    /**
     * @param array<string, mixed> $settings
     * @param array<string, mixed>|null $condition Fields condition tree
     * @param list<CanvasNode> $children
     */
    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly string $name,
        public readonly array $settings = [],
        public readonly ?array $condition = null,
        public readonly ?string $layoutId = null,
        public readonly array $children = [],
    ) {
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function withSettings(array $settings): self
    {
        return new self(
            $this->id,
            $this->type,
            $this->name,
            $settings,
            $this->condition,
            $this->layoutId,
            $this->children,
        );
    }

    /**
     * @param array<string, mixed>|null $condition
     */
    public function withCondition(?array $condition): self
    {
        return new self(
            $this->id,
            $this->type,
            $this->name,
            $this->settings,
            $condition,
            $this->layoutId,
            $this->children,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'settings' => $this->settings,
        ];

        if ($this->condition !== null) {
            $data['condition'] = $this->condition;
        }

        if ($this->layoutId !== null) {
            $data['layout_id'] = $this->layoutId;
        }

        if ($this->children !== []) {
            $data['children'] = array_map(
                static fn (CanvasNode $child): array => $child->toArray(),
                $this->children
            );
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $children = [];
        if (isset($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                if (is_array($child)) {
                    $children[] = self::fromArray($child);
                }
            }
        }

        $condition = null;
        if (isset($data['condition']) && is_array($data['condition'])) {
            $condition = $data['condition'];
        }

        return new self(
            (string) ($data['id'] ?? IdGenerator::node()),
            (string) ($data['type'] ?? 'text'),
            (string) ($data['name'] ?? 'field'),
            is_array($data['settings'] ?? null) ? $data['settings'] : [],
            $condition,
            isset($data['layout_id']) ? (string) $data['layout_id'] : null,
            $children,
        );
    }
}
