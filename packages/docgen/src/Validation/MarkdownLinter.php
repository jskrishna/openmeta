<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Validation;

use OpenMeta\Docgen\Model\DocPage;
use OpenMeta\Docgen\Model\IssueType;
use OpenMeta\Docgen\Model\ValidationIssue;
use OpenMeta\Docgen\Model\ValidationReport;

/**
 * Lightweight Markdown lint: every page needs a title (H1), and code fences
 * must not be empty.
 */
final class MarkdownLinter
{
    public function lint(DocPage $page): ValidationReport
    {
        $report = new ValidationReport();

        if ($page->title === '') {
            $report->add(new ValidationIssue(
                IssueType::MissingPage,
                $page->path,
                'missing a top-level title (# heading)',
            ));
        }

        foreach ($page->codeBlocks as $index => $block) {
            if (trim($block) === '') {
                $report->add(new ValidationIssue(
                    IssueType::EmptyCodeBlock,
                    $page->path,
                    sprintf('empty code block (#%d)', $index + 1),
                ));
            }
        }

        return $report;
    }
}
