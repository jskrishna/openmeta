<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Tests\Unit;

use OpenMeta\Extensions\Resources\ResourceLoader;
use OpenMeta\Extensions\Resources\ResourceType;
use OpenMeta\Extensions\Tests\ExtensionsTestCase;

final class ResourceLoaderTest extends ExtensionsTestCase
{
    public function test_registers_and_queries_by_type(): void
    {
        $loader = new ResourceLoader();
        $loader->field('stars', ['render' => 'stars'], 'acme/seo');
        $loader->route('GET /seo', ['handler' => 'x'], 'acme/seo');
        $loader->field('color', ['render' => 'color'], 'acme/ui');

        self::assertCount(3, $loader->all());
        self::assertCount(2, $loader->ofType(ResourceType::Field));
        self::assertCount(1, $loader->ofType(ResourceType::Route));
    }

    public function test_queries_by_extension(): void
    {
        $loader = new ResourceLoader();
        $loader->field('stars', null, 'acme/seo');
        $loader->menu('seo-menu', null, 'acme/seo');
        $loader->widget('promo', null, 'acme/ads');

        self::assertCount(2, $loader->forExtension('acme/seo'));
        self::assertCount(1, $loader->forExtension('acme/ads'));
    }

    public function test_payload_is_preserved(): void
    {
        $loader = new ResourceLoader();
        $loader->register(ResourceType::Template, 'hero', ['file' => 'hero.php']);

        self::assertSame(['file' => 'hero.php'], $loader->ofType(ResourceType::Template)[0]->payload);
    }
}
