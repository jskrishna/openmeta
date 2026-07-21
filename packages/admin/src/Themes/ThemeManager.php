<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Themes;

use OpenMeta\Ui\Theme\Theme;
use OpenMeta\Ui\Tokens\Tokens;

/**
 * Admin theme + design tokens (no CSS framework).
 */
final class ThemeManager
{
    public function __construct(private readonly Theme $theme)
    {
    }

    public function theme(): Theme
    {
        return $this->theme;
    }

    public function wrap(string $html): string
    {
        return $this->theme->wrap($html);
    }

    /**
     * @return array<string, string>
     */
    public function cssVariables(): array
    {
        return Tokens::all();
    }
}
