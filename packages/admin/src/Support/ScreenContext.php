<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Support;

/**
 * Screen regions passed into layout composers.
 */
final class ScreenContext
{
    /**
     * @param callable(): string|null $toolbar
     * @param callable(): string|null $content
     * @param callable(): string|null $sidebar
     * @param callable(): string|null $footer
     * @param callable(): string|null $actions
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $description = null,
        private readonly mixed $toolbar = null,
        private readonly mixed $content = null,
        private readonly mixed $sidebar = null,
        private readonly mixed $footer = null,
        private readonly mixed $actions = null,
    ) {
    }

    public function toolbar(): string
    {
        return $this->renderRegion($this->toolbar);
    }

    public function content(): string
    {
        return $this->renderRegion($this->content);
    }

    public function sidebar(): string
    {
        return $this->renderRegion($this->sidebar);
    }

    public function footer(): string
    {
        return $this->renderRegion($this->footer);
    }

    public function actions(): string
    {
        return $this->renderRegion($this->actions);
    }

    private function renderRegion(mixed $region): string
    {
        if ($region === null) {
            return '';
        }

        if (is_callable($region)) {
            return (string) $region();
        }

        return (string) $region;
    }
}
