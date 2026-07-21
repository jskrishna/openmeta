<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Unit;

use OpenMeta\Wordpress\Assets\AssetManager;
use OpenMeta\Wordpress\Configuration\PluginConfiguration;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class AssetManagerTest extends WordpressTestCase
{
    public function test_register_script_and_style(): void
    {
        $config = PluginConfiguration::fromPluginFile($this->plugin->pluginFile());
        $assets = new AssetManager($this->wp, $config);

        $this->assertTrue($assets->registerScript('openmeta-admin', '/assets/admin.js', ['jquery']));
        $this->assertTrue($assets->registerStyle('openmeta-admin', '/assets/admin.css'));

        $assets->enqueueScript('openmeta-admin');
        $assets->enqueueStyle('openmeta-admin');

        $this->assertCount(1, $this->wp->scripts);
        $this->assertSame('0.8.0-alpha', $this->wp->scripts[0]['version']);
        $this->assertContains('openmeta-admin', $this->wp->enqueuedScripts);
        $this->assertContains('openmeta-admin', $this->wp->enqueuedStyles);
    }
}
