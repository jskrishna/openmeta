<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Validation;

use OpenMeta\Docgen\Contracts\DocValidatorInterface;
use OpenMeta\Docgen\Model\ValidationReport;

/**
 * Runs the full documentation validation pass: markdown lint + link checking
 * across every page.
 */
final class DocValidator implements DocValidatorInterface
{
    public function __construct(
        private readonly LinkValidator $links,
        private readonly MarkdownLinter $linter,
    ) {
    }

    public function validate(array $pages): ValidationReport
    {
        $report = new ValidationReport();

        foreach ($pages as $page) {
            $report->merge($this->linter->lint($page));
            $report->merge($this->links->validate($page));
        }

        return $report;
    }
}
