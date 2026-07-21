<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\WordPress;

use OpenMeta\Admin\Tests\AdminTestCase;

/** Admin menus register in-memory; WP add_menu_page bridged by wordpress package. */
final class WordPressGateTest extends AdminTestCase
{
    public function test_admin_screens_exist_without_wp(): void
    {
        $this->assertTrue($this->admin->screens()->has('openmeta-settings'));
    }
}
