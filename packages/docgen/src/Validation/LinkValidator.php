<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Validation;

use OpenMeta\Docgen\Model\DocPage;
use OpenMeta\Docgen\Model\IssueType;
use OpenMeta\Docgen\Model\ValidationIssue;
use OpenMeta\Docgen\Model\ValidationReport;
use OpenMeta\Docgen\Support\PathNormalizer;
use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Verifies that every relative link in a page points at an existing file or
 * directory. External (http/mailto), anchor-only, and image links are skipped.
 */
final class LinkValidator
{
    public function __construct(private readonly FilesystemInterface $filesystem)
    {
    }

    public function validate(DocPage $page): ValidationReport
    {
        $report = new ValidationReport();
        $baseDir = PathNormalizer::directoryOf($page->path);

        foreach ($page->links as $link) {
            $url = $link['url'];

            if ($this->isExternal($url)) {
                continue;
            }

            $target = strtok($url, '#');
            $target = $target === false ? '' : strtok($target, '?');

            if ($target === false || $target === '') {
                continue;
            }

            $resolved = PathNormalizer::resolve($baseDir, $target);

            if (! $this->filesystem->isFile($resolved) && ! $this->filesystem->isDirectory($resolved)) {
                $report->add(new ValidationIssue(
                    IssueType::BrokenLink,
                    $page->path,
                    sprintf('broken link [%s](%s)', $link['text'], $url),
                ));
            }
        }

        return $report;
    }

    private function isExternal(string $url): bool
    {
        return $url === ''
            || str_starts_with($url, '#')
            || str_starts_with($url, 'http://')
            || str_starts_with($url, 'https://')
            || str_starts_with($url, 'mailto:')
            || str_starts_with($url, '//');
    }
}
