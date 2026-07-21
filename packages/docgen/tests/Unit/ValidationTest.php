<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Tests\Unit;

use OpenMeta\Docgen\Model\DocPage;
use OpenMeta\Docgen\Model\IssueType;
use OpenMeta\Docgen\Tests\Fixtures\InMemoryFilesystem;
use OpenMeta\Docgen\Validation\DocValidator;
use OpenMeta\Docgen\Validation\LinkValidator;
use OpenMeta\Docgen\Validation\MarkdownLinter;
use PHPUnit\Framework\TestCase;

final class ValidationTest extends TestCase
{
    public function test_linter_flags_missing_title_and_empty_code(): void
    {
        $linter = new MarkdownLinter();

        $page = new DocPage('docs/x.md', '', codeBlocks: ['   ']);
        $report = $linter->lint($page);

        $types = array_map(static fn ($i): IssueType => $i->type, $report->issues());
        self::assertContains(IssueType::MissingPage, $types);
        self::assertContains(IssueType::EmptyCodeBlock, $types);
    }

    public function test_validator_combines_lint_and_links(): void
    {
        $validator = new DocValidator(new LinkValidator(new InMemoryFilesystem()), new MarkdownLinter());

        $pages = [
            new DocPage('docs/ok.md', 'Ok'),
            new DocPage('docs/bad.md', '', links: [['text' => 'x', 'url' => './missing.md']]),
        ];

        $report = $validator->validate($pages);

        self::assertFalse($report->isClean());
        self::assertNotEmpty($report->ofType(IssueType::BrokenLink));
        self::assertNotEmpty($report->ofType(IssueType::MissingPage));
    }
}
