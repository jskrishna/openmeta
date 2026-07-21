<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Schema;

use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Layouts\LayoutEngine;

/**
 * Builds portable, framework-independent schema documents.
 */
final class SchemaBuilder
{
    public function __construct(
        private readonly Canvas $canvas,
        private readonly LayoutEngine $layouts,
    ) {
    }

    /** @return array<string, mixed> */
    public function build(string $title = 'Untitled'): array
    {
        return [
            'version' => SchemaVersion::CURRENT,
            'title' => $title,
            'canvas' => $this->canvas->toArray(),
            'layouts' => $this->layouts->toArray(),
            'metadata' => [
                'generated_at' => gmdate('c'),
            ],
        ];
    }
}
