<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Pages;

/**
 * Admin page definition — maps to a screen with layout regions.
 */
final class Page
{
    /**
     * @param callable(): string|null $content
     * @param callable(): string|null $toolbar
     * @param callable(): string|null $sidebar
     * @param callable(): string|null $footer
     * @param callable(): string|null $actions
     */
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $permission,
        public readonly string $layout = 'full-width',
        public readonly ?string $description = null,
        private readonly mixed $content = null,
        private readonly mixed $toolbar = null,
        private readonly mixed $sidebar = null,
        private readonly mixed $footer = null,
        private readonly mixed $actions = null,
    ) {
    }

    public function content(): mixed
    {
        return $this->content;
    }

    public function toolbar(): mixed
    {
        return $this->toolbar;
    }

    public function sidebar(): mixed
    {
        return $this->sidebar;
    }

    public function footer(): mixed
    {
        return $this->footer;
    }

    public function actions(): mixed
    {
        return $this->actions;
    }
}
