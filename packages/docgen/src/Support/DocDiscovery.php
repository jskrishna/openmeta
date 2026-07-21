<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Support;

use FilesystemIterator;
use OpenMeta\Docgen\Model\DocPage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Discovers and parses every Markdown page under a docs directory.
 */
final class DocDiscovery
{
    /**
     * @return list<DocPage>
     */
    public function discover(string $docsDir): array
    {
        if (! is_dir($docsDir)) {
            return [];
        }

        $pages = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($docsDir, FilesystemIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if (! $file instanceof SplFileInfo || ! $file->isFile() || strtolower($file->getExtension()) !== 'md') {
                continue;
            }

            $path = str_replace('\\', '/', $file->getPathname());
            $pages[] = MarkdownDocument::parse($path, (string) file_get_contents($file->getPathname()));
        }

        usort($pages, static fn (DocPage $a, DocPage $b): int => strcmp($a->path, $b->path));

        return $pages;
    }
}
