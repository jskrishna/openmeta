<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Tests\Unit;

use OpenMeta\Sdk\Discovery\ComposerDiscovery;
use OpenMeta\Sdk\Discovery\DirectoryDiscovery;
use OpenMeta\Sdk\Discovery\DiscoveryManager;
use OpenMeta\Sdk\Discovery\ManualDiscovery;
use OpenMeta\Sdk\Manifest\ManifestFactory;
use OpenMeta\Sdk\Tests\Fixtures\InMemoryFilesystem;
use OpenMeta\Sdk\Tests\SdkTestCase;

final class DiscoveryTest extends SdkTestCase
{
    public function test_manual_discovery_returns_added_manifests(): void
    {
        $discovery = new ManualDiscovery();
        $discovery->add($this->manifest('acme/a'));

        self::assertCount(1, $discovery->discover());
        self::assertSame('acme/a', $discovery->discover()[0]->packageId());
    }

    public function test_directory_discovery_reads_json_files(): void
    {
        $path = '/ext/seo/openmeta.extension.json';
        $filesystem = new InMemoryFilesystem([
            $path => (string) json_encode([
                'packageId' => 'acme/seo',
                'name' => 'SEO',
                'vendor' => 'acme',
                'version' => '1.0.0',
            ]),
        ]);

        $discovery = new DirectoryDiscovery($filesystem, new ManifestFactory(), [$path]);

        self::assertCount(1, $discovery->discover());
        self::assertSame('acme/seo', $discovery->discover()[0]->packageId());
    }

    public function test_composer_discovery_reads_extension_packages(): void
    {
        $installed = (string) json_encode([
            'packages' => [
                [
                    'name' => 'acme/seo',
                    'version' => '2.1.0',
                    'type' => 'openmeta-extension',
                    'description' => 'SEO',
                    'license' => ['MIT'],
                ],
                [
                    'name' => 'vendor/unrelated',
                    'version' => '1.0.0',
                    'type' => 'library',
                ],
            ],
        ]);
        $filesystem = new InMemoryFilesystem(['/vendor/composer/installed.json' => $installed]);

        $discovery = new ComposerDiscovery($filesystem, new ManifestFactory(), '/vendor/composer/installed.json');
        $manifests = $discovery->discover();

        self::assertCount(1, $manifests);
        self::assertSame('acme/seo', $manifests[0]->packageId());
        self::assertSame('2.1.0', $manifests[0]->version());
        self::assertSame('MIT', $manifests[0]->license());
    }

    public function test_discovery_manager_dedupes_by_package_id(): void
    {
        $first = new ManualDiscovery([$this->manifest('acme/a', '1.0.0')]);
        $second = new ManualDiscovery([$this->manifest('acme/a', '2.0.0'), $this->manifest('acme/b')]);

        $manager = new DiscoveryManager([$first, $second]);
        $manifests = $manager->discover();

        self::assertCount(2, $manifests);
        // First source wins on conflict.
        self::assertSame('1.0.0', $manifests[0]->version());
    }
}
