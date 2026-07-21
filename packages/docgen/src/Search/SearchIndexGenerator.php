<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Search;

use OpenMeta\Docgen\Model\DocPage;

/**
 * Builds a JSON full-text search index (title, path, headings, tags) that a
 * static front-end can filter by package, version, or tag.
 */
final class SearchIndexGenerator
{
    /**
     * @param list<DocPage> $pages
     */
    public function build(array $pages): string
    {
        $documents = [];

        foreach ($pages as $page) {
            $documents[] = [
                'title' => $page->title !== '' ? $page->title : basename($page->path),
                'path' => $page->path,
                'headings' => $page->headings,
                'tags' => $page->tags,
            ];
        }

        return (string) json_encode(
            ['documents' => $documents],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
        ) . "\n";
    }
}
