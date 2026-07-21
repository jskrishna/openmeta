<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Tests\Unit;

use OpenMeta\Docgen\Api\ApiDocRenderer;
use OpenMeta\Docgen\Changelog\ChangelogGenerator;
use OpenMeta\Docgen\Model\DocPage;
use OpenMeta\Docgen\Model\MethodDoc;
use OpenMeta\Docgen\Model\TypeDoc;
use OpenMeta\Docgen\Packages\PackageDocGenerator;
use OpenMeta\Docgen\Search\SearchIndexGenerator;
use OpenMeta\Docgen\Sitemap\SitemapGenerator;
use OpenMeta\Docgen\Tests\Fixtures\InMemoryFilesystem;
use PHPUnit\Framework\TestCase;

final class GeneratorsTest extends TestCase
{
    public function test_api_renderer_outputs_type_and_index(): void
    {
        $type = new TypeDoc(
            'App\\Fields\\Star',
            'class',
            'A star field.',
            [new MethodDoc('type', 'type(): string', 'The field type.')],
            ['VERSION'],
        );

        $page = (new ApiDocRenderer())->renderPackage('fields', [$type]);
        self::assertStringContainsString('# API Reference — `fields`', $page);
        self::assertStringContainsString('## Star', $page);
        self::assertStringContainsString('`type(): string`', $page);
        self::assertStringContainsString('`VERSION`', $page);

        $index = (new ApiDocRenderer())->renderIndex(['fields' => 3, 'core' => 12]);
        self::assertStringContainsString('[core](./core.md) | 12', $index);
    }

    public function test_search_index_is_json(): void
    {
        $json = (new SearchIndexGenerator())->build([
            new DocPage('docs/a.md', 'Alpha', ['Alpha', 'Intro'], ['guide']),
        ]);

        $data = json_decode($json, true);
        self::assertIsArray($data);
        self::assertSame('Alpha', $data['documents'][0]['title']);
        self::assertSame(['guide'], $data['documents'][0]['tags']);
    }

    public function test_sitemap_is_xml(): void
    {
        $xml = (new SitemapGenerator())->build([new DocPage('docs/a.md', 'A')], 'https://openmeta.dev');

        self::assertStringContainsString('<urlset', $xml);
        self::assertStringContainsString('<loc>https://openmeta.dev/docs/a.md</loc>', $xml);
    }

    public function test_changelog_wraps_source(): void
    {
        $out = (new ChangelogGenerator())->build("# Changelog\n\n## v1.0.0\n\n- first");

        self::assertStringContainsString('# Changelog', $out);
        self::assertStringContainsString('Generated reference', $out);
        self::assertStringContainsString('## v1.0.0', $out);
    }

    public function test_package_doc_renders_table(): void
    {
        $md = (new PackageDocGenerator(new InMemoryFilesystem()))->render([
            ['name' => 'openmeta/core', 'version' => '0.1.0-alpha', 'description' => 'Core'],
        ]);

        self::assertStringContainsString('| Package | Version | Description |', $md);
        self::assertStringContainsString('`openmeta/core`', $md);
    }
}
