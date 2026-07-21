<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Tokens;

/**
 * Design tokens — CSS variable contract (no domain meanings).
 */
final class Tokens
{
    /** @return array<string, string> */
    public static function all(): array
    {
        return [
            '--om-color-fg' => '#1a1a1a',
            '--om-color-bg' => '#ffffff',
            '--om-color-muted' => '#6b7280',
            '--om-color-accent' => '#0f766e',
            '--om-color-border' => '#e5e7eb',
            '--om-color-danger' => '#b91c1c',
            '--om-space-1' => '0.25rem',
            '--om-space-2' => '0.5rem',
            '--om-space-3' => '0.75rem',
            '--om-space-4' => '1rem',
            '--om-radius' => '0.375rem',
            '--om-font-sans' => 'system-ui, sans-serif',
        ];
    }

    public static function css(): string
    {
        $lines = [':root {'];

        foreach (self::all() as $name => $value) {
            $lines[] = '  ' . $name . ': ' . $value . ';';
        }

        $lines[] = '}';

        return implode("\n", $lines);
    }
}
