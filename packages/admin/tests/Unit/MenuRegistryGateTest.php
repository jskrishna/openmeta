<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Unit;

use OpenMeta\Admin\Menus\MenuItem;
use OpenMeta\Admin\Menus\MenuRegistry;
use OpenMeta\Admin\Tests\AdminTestCase;
use OpenMeta\Security\Permissions\Permission;

final class MenuRegistryGateTest extends AdminTestCase
{
    public function test_menu_registration(): void
    {
        $menus = new MenuRegistry();
        $menus->add(new MenuItem('x', 'X', 'screen-x', Permission::MANAGE));
        $this->assertTrue($menus->has('x'));
    }
}
