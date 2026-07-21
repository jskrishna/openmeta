<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Support;

use OpenMeta\Docgen\Model\DocPage;

/**
 * Parses a Markdown document into a {@see DocPage}: title, headings, tags,
 * links, and code blocks.
 *
 * Fenced code is scanned line-by-line and is **fence-length aware**, so a
 * document wrapped in a 4-backtick fence (with inner 3-backtick blocks) is
 * treated as a single block — no false "empty code block" or in-code links.
 */
final class MarkdownDocument
{
    public static function parse(string $path, string $content): DocPage
    {
        $scan = self::scanFences($content);

        return new DocPage(
            $path,
            self::title($content),
            self::headings($content),
            self::tags($content),
            // Links are read from the code-stripped body so in-code links are not validated.
            self::links($scan['stripped']),
            $scan['blocks'],
            $content,
        );
    }

    /**
     * @return array{blocks: list<string>, stripped: string}
     */
    private static function scanFences(string $content): array
    {
        $blocks = [];
        $stripped = [];
        $fence = 0;
        $buffer = '';

        foreach (explode("\n", $content) as $line) {
            $trimmed = ltrim($line);

            if (preg_match('/^(`{3,})/', $trimmed, $match) === 1) {
                $length = strlen($match[1]);

                if ($fence === 0) {
                    $fence = $length;
                    $buffer = '';

                    continue;
                }

                if ($length >= $fence) {
                    $blocks[] = $buffer;
                    $fence = 0;

                    continue;
                }
            }

            if ($fence > 0) {
                $buffer .= $line . "\n";
            } else {
                $stripped[] = $line;
            }
        }

        if ($fence > 0) {
            $blocks[] = $buffer;
        }

        return ['blocks' => $blocks, 'stripped' => implode("\n", $stripped)];
    }

    private static function title(string $content): string
    {
        if (preg_match('/^#\s+(.+)$/m', $content, $match) === 1) {
            return trim($match[1]);
        }

        return '';
    }

    /**
     * @return list<string>
     */
    private static function headings(string $content): array
    {
        preg_match_all('/^#{1,6}\s+(.+)$/m', $content, $matches);

        return array_map('trim', $matches[1]);
    }

    /**
     * @return list<string>
     */
    private static function tags(string $content): array
    {
        if (preg_match('/^tags:\s*\[?([^\]\n]+)\]?\s*$/mi', $content, $match) !== 1) {
            return [];
        }

        $tags = array_map('trim', explode(',', $match[1]));

        return array_values(array_filter($tags, static fn (string $tag): bool => $tag !== ''));
    }

    /**
     * @return list<array{text: string, url: string}>
     */
    private static function links(string $content): array
    {
        preg_match_all('/(?<!\!)\[([^\]]+)\]\(([^)\s]+)/', $content, $matches, PREG_SET_ORDER);

        $links = [];

        foreach ($matches as $match) {
            $links[] = ['text' => $match[1], 'url' => $match[2]];
        }

        return $links;
    }
}
