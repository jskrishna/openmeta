<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Theme;

use OpenMeta\Ui\Tokens\Tokens;

/**
 * Applies tokens to a runtime theme scope.
 */
final class Theme
{
    /** @param array<string, string> $overrides */
    public function __construct(private readonly array $overrides = [])
    {
    }

    public function css(): string
    {
        $tokens = array_merge(Tokens::all(), $this->overrides);
        $lines = ['.om-theme {'];

        foreach ($tokens as $name => $value) {
            $lines[] = '  ' . $name . ': ' . $value . ';';
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }

    public function wrap(string $html): string
    {
        return '<div class="om-theme">' . $html . '</div>';
    }
}
