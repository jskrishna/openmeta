<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Contracts;

use OpenMeta\Docgen\Model\DocPage;
use OpenMeta\Docgen\Model\ValidationReport;

/**
 * Validates a set of documentation pages.
 */
interface DocValidatorInterface
{
    /**
     * @param list<DocPage> $pages
     */
    public function validate(array $pages): ValidationReport;
}
