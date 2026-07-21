<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Panels;

use OpenMeta\Ui\Components\Card;

/**
 * Collapsible content panel wrapper.
 */
final class Panel
{
    public function __construct(
        private readonly string $title,
        private readonly string $body,
        private readonly bool $collapsed = false,
    ) {
    }

    public function render(): string
    {
        $class = 'om-panel' . ($this->collapsed ? ' om-panel--collapsed' : '');

        return '<section class="' . $class . '">' . Card::render($this->title, $this->body) . '</section>';
    }
}
