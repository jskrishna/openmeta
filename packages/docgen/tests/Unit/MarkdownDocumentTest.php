<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Tests\Unit;

use OpenMeta\Docgen\Support\MarkdownDocument;
use PHPUnit\Framework\TestCase;

final class MarkdownDocumentTest extends TestCase
{
    public function test_parses_title_and_headings(): void
    {
        $page = MarkdownDocument::parse('docs/x.md', "# Title\n\n## Section\n\nbody\n\n### Sub");

        self::assertSame('Title', $page->title);
        self::assertSame(['Title', 'Section', 'Sub'], $page->headings);
    }

    public function test_extracts_links_but_not_images_or_code(): void
    {
        $content = "[good](./a.md) ![img](x.png)\n\n```\n[incode](./nope.md)\n```";
        $page = MarkdownDocument::parse('docs/x.md', $content);

        $urls = array_map(static fn (array $l): string => $l['url'], $page->links);
        self::assertSame(['./a.md'], $urls);
    }

    public function test_extracts_tags_and_code_blocks(): void
    {
        $content = "# T\n\ntags: alpha, beta\n\n```php\necho 1;\n```";
        $page = MarkdownDocument::parse('docs/x.md', $content);

        self::assertSame(['alpha', 'beta'], $page->tags);
        self::assertCount(1, $page->codeBlocks);
        self::assertStringContainsString('echo 1;', $page->codeBlocks[0]);
    }
}
