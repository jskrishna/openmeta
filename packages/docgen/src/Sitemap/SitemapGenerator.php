<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Sitemap;

use OpenMeta\Docgen\Model\DocPage;

/**
 * Builds an XML sitemap of every documentation page.
 */
final class SitemapGenerator
{
    /**
     * @param list<DocPage> $pages
     */
    public function build(array $pages, string $baseUrl = '/'): string
    {
        $lines = [];
        $lines[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $lines[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($pages as $page) {
            $loc = rtrim($baseUrl, '/') . '/' . ltrim($page->path, '/');
            $lines[] = '  <url><loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc></url>';
        }

        $lines[] = '</urlset>';

        return implode("\n", $lines) . "\n";
    }
}
