<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Model;

/**
 * Categories of documentation validation issues.
 */
enum IssueType: string
{
    case BrokenLink = 'broken_link';
    case MissingPage = 'missing_page';
    case EmptyCodeBlock = 'empty_code_block';
    case MissingApiReference = 'missing_api_reference';
    case Lint = 'lint';
}
