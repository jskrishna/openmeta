<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Model;

/**
 * A parsed documentation page: its path, title, headings, tags, and links.
 */
final class DocPage
{
    /**
     * @param list<string>                          $headings
     * @param list<string>                          $tags
     * @param list<array{text: string, url: string}> $links
     * @param list<string>                          $codeBlocks
     */
    public function __construct(
        public readonly string $path,
        public readonly string $title,
        public readonly array $headings = [],
        public readonly array $tags = [],
        public readonly array $links = [],
        public readonly array $codeBlocks = [],
        public readonly string $content = '',
    ) {
    }
}
