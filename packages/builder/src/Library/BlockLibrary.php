<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Library;

use OpenMeta\Builder\Layouts\LayoutEngine;
use OpenMeta\Builder\Registry\ComponentDescriptor;
use OpenMeta\Builder\Registry\ComponentRegistry;
use OpenMeta\Builder\Templates\TemplateRegistry;

/**
 * Block library — components, layouts, patterns, favorites.
 */
final class BlockLibrary
{
    /** @var list<string> */
    private array $favorites = [];

    public function __construct(
        private readonly ComponentRegistry $components,
        private readonly LayoutEngine $layouts,
        private readonly TemplateRegistry $templates,
    ) {
    }

    /** @return array<string, mixed> */
    public function catalog(): array
    {
        return [
            'components' => array_map(
                static fn (ComponentDescriptor $descriptor): array => $descriptor->toArray(),
                $this->components->all()
            ),
            'layouts' => $this->layouts->toArray(),
            'patterns' => $this->templates->list(),
            'favorites' => $this->favorites,
        ];
    }

    public function favorite(string $id): void
    {
        if (! in_array($id, $this->favorites, true)) {
            $this->favorites[] = $id;
        }
    }

    public function unfavorite(string $id): void
    {
        $this->favorites = array_values(array_filter(
            $this->favorites,
            static fn (string $favorite): bool => $favorite !== $id
        ));
    }
}
