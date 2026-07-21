<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Widgets;

/**
 * Dashboard widget definition.
 */
final class DashboardWidget
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        private readonly mixed $renderCallback,
        public readonly int $priority = 10,
    ) {
    }

    public function render(): string
    {
        return is_callable($this->renderCallback) ? (string) ($this->renderCallback)() : '';
    }
}
