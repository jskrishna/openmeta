<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Cards;

use OpenMeta\Admin\Components\ComponentDescriptor;
use OpenMeta\Admin\Contracts\RenderableComponentInterface;

/**
 * Card component descriptor (UI renders markup).
 */
final class CardComponent implements RenderableComponentInterface
{
    public function render(array $props = []): ComponentDescriptor
    {
        return new ComponentDescriptor('card', [
            'title' => (string) ($props['title'] ?? ''),
            'body' => (string) ($props['body'] ?? ''),
        ]);
    }
}
