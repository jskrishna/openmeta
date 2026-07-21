<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Tests\Unit;

use OpenMeta\Sdk\Exceptions\InvalidManifestException;
use OpenMeta\Sdk\Manifest\ManifestFactory;
use OpenMeta\Sdk\Tests\SdkTestCase;

final class ManifestFactoryTest extends SdkTestCase
{
    private ManifestFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new ManifestFactory();
    }

    public function test_parses_full_manifest(): void
    {
        $manifest = $this->factory->fromArray([
            'packageId' => 'acme/seo',
            'name' => 'SEO',
            'vendor' => 'acme',
            'version' => '1.4.0',
            'description' => 'SEO tools',
            'author' => 'Acme',
            'license' => 'GPL-2.0-or-later',
            'minimumCoreVersion' => '0.10.0',
            'maximumCoreVersion' => '1.0.0',
            'providers' => ['Acme\\Seo\\SeoServiceProvider'],
            'permissions' => ['manage_seo'],
            'featureFlags' => ['sitemaps' => true, 'redirects' => false],
            'requirements' => [
                'php' => '>=8.3',
                'wordpress' => '>=6.4',
                'phpExtensions' => ['json', 'mbstring'],
            ],
            'dependencies' => [
                'acme/core-kit' => '^1.0',
            ],
        ]);

        self::assertSame('acme/seo', $manifest->packageId());
        self::assertSame('1.4.0', $manifest->version());
        self::assertSame('0.10.0', $manifest->minimumCoreVersion());
        self::assertSame(['Acme\\Seo\\SeoServiceProvider'], $manifest->providers());
        self::assertSame(['manage_seo'], $manifest->permissions());
        self::assertTrue($manifest->featureFlags()['sitemaps']);
        self::assertSame('>=8.3', $manifest->requirements()->php);
        self::assertSame(['json', 'mbstring'], $manifest->requirements()->phpExtensions);
        self::assertCount(1, $manifest->dependencies());
        self::assertSame('acme/core-kit', $manifest->dependencies()[0]->packageId);
        self::assertSame('^1.0', $manifest->dependencies()[0]->constraint);
    }

    public function test_parses_list_form_dependencies(): void
    {
        $manifest = $this->factory->fromArray([
            'packageId' => 'acme/seo',
            'name' => 'SEO',
            'vendor' => 'acme',
            'version' => '1.0.0',
            'dependencies' => [
                ['package' => 'acme/a', 'constraint' => '^1.0', 'optional' => false],
                ['package' => 'acme/b', 'optional' => true],
            ],
        ]);

        self::assertCount(2, $manifest->dependencies());
        self::assertTrue($manifest->dependencies()[0]->isRequired());
        self::assertTrue($manifest->dependencies()[1]->optional);
        self::assertSame('*', $manifest->dependencies()[1]->constraint);
    }

    public function test_missing_required_field_throws(): void
    {
        $this->expectException(InvalidManifestException::class);

        $this->factory->fromArray([
            'name' => 'SEO',
            'vendor' => 'acme',
            'version' => '1.0.0',
        ]);
    }

    public function test_from_json_rejects_invalid_json(): void
    {
        $this->expectException(InvalidManifestException::class);

        $this->factory->fromJson('{not-json', 'manifest.json');
    }

    public function test_round_trips_through_to_array(): void
    {
        $original = $this->factory->fromArray([
            'packageId' => 'acme/seo',
            'name' => 'SEO',
            'vendor' => 'acme',
            'version' => '1.0.0',
            'dependencies' => ['acme/a' => '^1.0'],
        ]);

        $rebuilt = $this->factory->fromArray($original->toArray());

        self::assertSame($original->packageId(), $rebuilt->packageId());
        self::assertSame(
            $original->dependencies()[0]->packageId,
            $rebuilt->dependencies()[0]->packageId
        );
    }
}
