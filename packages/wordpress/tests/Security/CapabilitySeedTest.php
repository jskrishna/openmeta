<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Security;

use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class CapabilitySeedTest extends WordpressTestCase
{
    public function test_activate_seeds_openmeta_capabilities(): void
    {
        $this->plugin->activate();
        $caps = array_column($this->wp->capabilities, 'capability');
        $this->assertContains('openmeta.manage', $caps);
    }
}
