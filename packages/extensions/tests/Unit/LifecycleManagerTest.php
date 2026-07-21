<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Tests\Unit;

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Events\EventDispatcher;
use OpenMeta\Extensions\Events\ExtensionActivated;
use OpenMeta\Extensions\Events\ExtensionDeactivated;
use OpenMeta\Extensions\Events\ExtensionInstalled;
use OpenMeta\Extensions\Events\ExtensionRemoved;
use OpenMeta\Extensions\Events\ExtensionUpdated;
use OpenMeta\Extensions\Exceptions\ExtensionNotFoundException;
use OpenMeta\Extensions\Exceptions\LifecycleException;
use OpenMeta\Extensions\Lifecycle\ExtensionState;
use OpenMeta\Extensions\Lifecycle\LifecycleManager;
use OpenMeta\Extensions\Providers\ServiceProviderLoader;
use OpenMeta\Extensions\Registry\ExtensionRegistry;
use OpenMeta\Extensions\Support\FeatureFlags;
use OpenMeta\Extensions\Tests\Fixtures\EventRecorder;
use OpenMeta\Extensions\Tests\Fixtures\RecordingProvider;
use OpenMeta\Extensions\Tests\ExtensionsTestCase;
use OpenMeta\Extensions\Versioning\VersionComparator;
use OpenMeta\Extensions\Versioning\VersionManager;

final class LifecycleManagerTest extends ExtensionsTestCase
{
    private LifecycleManager $lifecycle;

    private ExtensionRegistry $registry;

    private EventRecorder $recorder;

    protected function setUp(): void
    {
        parent::setUp();

        RecordingProvider::reset();

        $this->registry = new ExtensionRegistry();
        $events = new EventDispatcher();
        $this->recorder = new EventRecorder();
        $this->recorder->listenTo($events, [
            ExtensionInstalled::class,
            ExtensionActivated::class,
            ExtensionDeactivated::class,
            ExtensionUpdated::class,
            ExtensionRemoved::class,
        ]);

        $this->lifecycle = new LifecycleManager(
            $this->registry,
            new ServiceProviderLoader(),
            new Container(),
            $events,
            new FeatureFlags(),
            new VersionManager(new VersionComparator()),
        );
    }

    public function test_install_registers_and_emits_event(): void
    {
        $extension = $this->lifecycle->install($this->manifest('acme/a'));

        self::assertSame(ExtensionState::Installed, $extension->state());
        self::assertTrue($this->registry->has('acme/a'));
        self::assertSame(1, $this->recorder->count(ExtensionInstalled::class));
    }

    public function test_install_twice_throws(): void
    {
        $this->lifecycle->install($this->manifest('acme/a'));

        $this->expectException(LifecycleException::class);
        $this->lifecycle->install($this->manifest('acme/a'));
    }

    public function test_activate_loads_providers_and_activates(): void
    {
        $this->lifecycle->install($this->manifest('acme/a', providers: [RecordingProvider::class]));
        $extension = $this->lifecycle->activate('acme/a');

        self::assertSame(ExtensionState::Active, $extension->state());
        self::assertSame(1, RecordingProvider::$registered);
        self::assertSame(1, RecordingProvider::$booted);
        self::assertSame(1, $this->recorder->count(ExtensionActivated::class));
    }

    public function test_deactivate_returns_to_installed(): void
    {
        $this->lifecycle->install($this->manifest('acme/a'));
        $this->lifecycle->activate('acme/a');
        $extension = $this->lifecycle->deactivate('acme/a');

        self::assertSame(ExtensionState::Installed, $extension->state());
        self::assertSame(1, $this->recorder->count(ExtensionDeactivated::class));
    }

    public function test_cannot_deactivate_when_not_active(): void
    {
        $this->lifecycle->install($this->manifest('acme/a'));

        $this->expectException(LifecycleException::class);
        $this->lifecycle->deactivate('acme/a');
    }

    public function test_disable_blocks_and_can_reactivate(): void
    {
        $this->lifecycle->install($this->manifest('acme/a'));
        $disabled = $this->lifecycle->disable('acme/a');
        self::assertSame(ExtensionState::Disabled, $disabled->state());

        $reactivated = $this->lifecycle->activate('acme/a');
        self::assertSame(ExtensionState::Active, $reactivated->state());
    }

    public function test_update_replaces_version_and_emits_event(): void
    {
        $this->lifecycle->install($this->manifest('acme/a', '1.0.0'));
        $updated = $this->lifecycle->update($this->manifest('acme/a', '1.1.0'));

        self::assertSame('1.1.0', $updated->manifest()->version());
        self::assertSame(1, $this->recorder->count(ExtensionUpdated::class));
    }

    public function test_uninstall_removes_and_emits_event(): void
    {
        $this->lifecycle->install($this->manifest('acme/a'));
        $this->lifecycle->uninstall('acme/a');

        self::assertFalse($this->registry->has('acme/a'));
        self::assertSame(1, $this->recorder->count(ExtensionRemoved::class));
    }

    public function test_activate_unknown_extension_throws(): void
    {
        $this->expectException(ExtensionNotFoundException::class);
        $this->lifecycle->activate('acme/missing');
    }
}
