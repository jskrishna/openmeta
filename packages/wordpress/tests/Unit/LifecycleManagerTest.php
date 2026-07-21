<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Unit;

use OpenMeta\Core\Events\EventDispatcher;
use OpenMeta\Wordpress\Configuration\PluginConfiguration;
use OpenMeta\Wordpress\Events\PluginActivated;
use OpenMeta\Wordpress\Lifecycle\LifecycleManager;
use OpenMeta\Wordpress\Lifecycle\UpgradeManager;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class LifecycleManagerTest extends WordpressTestCase
{
    public function test_activate_dispatches_event_and_fires_action(): void
    {
        $config = PluginConfiguration::fromPluginFile($this->plugin->pluginFile());
        $events = new EventDispatcher();
        $activated = false;

        $events->listen(PluginActivated::class, static function () use (&$activated): void {
            $activated = true;
        });

        $lifecycle = new LifecycleManager(
            $this->wp,
            $events,
            new UpgradeManager($this->wp, $config),
            $config,
        );

        $fired = false;
        $this->wp->addAction('openmeta_activate', static function () use (&$fired): void {
            $fired = true;
        });

        $lifecycle->activate();

        $this->assertTrue($activated);
        $this->assertTrue($fired);
        $this->assertSame('0.8.0-alpha', $this->wp->getOption('openmeta_version'));
    }
}
