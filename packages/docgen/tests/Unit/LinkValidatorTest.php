<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Tests\Unit;

use OpenMeta\Docgen\Model\DocPage;
use OpenMeta\Docgen\Model\IssueType;
use OpenMeta\Docgen\Tests\Fixtures\InMemoryFilesystem;
use OpenMeta\Docgen\Validation\LinkValidator;
use PHPUnit\Framework\TestCase;

final class LinkValidatorTest extends TestCase
{
    public function test_flags_broken_relative_links(): void
    {
        $fs = new InMemoryFilesystem(['docs/a.md' => 'x']);
        $validator = new LinkValidator($fs);

        $page = new DocPage('docs/index.md', 'Index', links: [
            ['text' => 'ok', 'url' => './a.md'],
            ['text' => 'missing', 'url' => './gone.md'],
        ]);

        $report = $validator->validate($page);

        self::assertCount(1, $report->issues());
        self::assertSame(IssueType::BrokenLink, $report->issues()[0]->type);
        self::assertStringContainsString('gone.md', $report->issues()[0]->message);
    }

    public function test_ignores_external_and_anchor_links(): void
    {
        $validator = new LinkValidator(new InMemoryFilesystem());

        $page = new DocPage('docs/index.md', 'Index', links: [
            ['text' => 'web', 'url' => 'https://example.com'],
            ['text' => 'mail', 'url' => 'mailto:a@b.com'],
            ['text' => 'anchor', 'url' => '#section'],
        ]);

        self::assertTrue($validator->validate($page)->isClean());
    }

    public function test_strips_anchor_before_resolving(): void
    {
        $fs = new InMemoryFilesystem(['docs/a.md' => 'x']);
        $validator = new LinkValidator($fs);

        $page = new DocPage('docs/index.md', 'Index', links: [
            ['text' => 'deep', 'url' => './a.md#heading'],
        ]);

        self::assertTrue($validator->validate($page)->isClean());
    }
}
