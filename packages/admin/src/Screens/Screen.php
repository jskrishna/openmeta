<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Screens;

/**
 * Admin page / screen definition.
 */
final class Screen
{
    /**
     * @param callable(): string $renderer
     */
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $permission,
        public readonly mixed $renderer,
    ) {
    }
}
