<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Changelog;

/**
 * Renders the repository CHANGELOG.md into a documentation reference page.
 */
final class ChangelogGenerator
{
    public function build(string $changelog): string
    {
        // Drop the source top-level heading; we supply our own.
        $body = (string) preg_replace('/^#\s+.*$/m', '', $changelog, 1);

        $lines = [];
        $lines[] = '# Changelog';
        $lines[] = '';
        $lines[] = '_Generated reference — the source of truth is the repository `CHANGELOG.md`._';
        $lines[] = '';
        $lines[] = trim($body);

        return implode("\n", $lines) . "\n";
    }
}
