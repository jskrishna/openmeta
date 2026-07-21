<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Tests\Integration;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Sdk\Compatibility\Environment;
use OpenMeta\Sdk\Contracts\ExtensionManagerInterface;
use OpenMeta\Sdk\Discovery\ManualDiscovery;
use OpenMeta\Sdk\Lifecycle\ExtensionState;
use OpenMeta\Sdk\Manifest\Dependency;
use OpenMeta\Sdk\SdkServiceProvider;
use OpenMeta\Sdk\Tests\Fixtures\RecordingProvider;
use OpenMeta\Sdk\Tests\SdkTestCase;

/**
 * Full-stack flow: boot the framework with the SDK provider, seed discovery,
 * and bootstrap extensions in dependency order.
 */
final class ExtensionBootstrapTest extends SdkTestCase
{
    public function test_bootstrap_activates_compatible_extensions_in_order(): void
    {
        RecordingProvider::reset();

        $app = Bootstrap::run(
            ['app' => ['key' => 'sdk-test-secret']],
            [SdkServiceProvider::class],
        );

        /** @var ManualDiscovery $discovery */
        $discovery = $app->get(ManualDiscovery::class);
        $discovery->add($this->manifest('acme/base', '1.0.0', providers: [RecordingProvider::class]));
        $discovery->add($this->manifest('acme/feature', '1.0.0', dependencies: [new Dependency('acme/base', '^1.0')]));

        /** @var ExtensionManagerInterface $manager */
        $manager = $app->get(ExtensionManagerInterface::class);

        $report = $manager->bootstrap(new Environment('0.13.0', PHP_VERSION));

        self::assertSame(['acme/base', 'acme/feature'], $report->activated);
        self::assertSame(ExtensionState::Active, $manager->registry()->get('acme/feature')->state());
        self::assertGreaterThanOrEqual(1, RecordingProvider::$booted);
    }

    public function test_bootstrap_skips_incompatible_extension(): void
    {
        $app = Bootstrap::run(
            ['app' => ['key' => 'sdk-test-secret']],
            [SdkServiceProvider::class],
        );

        /** @var ManualDiscovery $discovery */
        $discovery = $app->get(ManualDiscovery::class);
        // Requires a newer core than the environment reports.
        $discovery->add($this->manifest('acme/too-new', '1.0.0', minCore: '9.0.0'));

        /** @var ExtensionManagerInterface $manager */
        $manager = $app->get(ExtensionManagerInterface::class);

        $report = $manager->bootstrap(new Environment('0.13.0', PHP_VERSION));

        self::assertSame([], $report->activated);
        self::assertArrayHasKey('acme/too-new', $report->skipped);
    }
}
