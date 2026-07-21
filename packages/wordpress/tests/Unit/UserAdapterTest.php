<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Unit;

use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Wordpress\Tests\WordpressTestCase;
use OpenMeta\Wordpress\Users\UserAdapter;

final class UserAdapterTest extends WordpressTestCase
{
    public function test_fail_closed_without_capabilities(): void
    {
        $adapter = new UserAdapter($this->wp, new ArrayCapabilityChecker([]));

        $this->assertSame(0, $adapter->currentUserId());
        $this->assertFalse($adapter->can('openmeta.manage'));
    }

    public function test_can_when_capability_granted_in_checker(): void
    {
        $this->wp->currentUserId = 5;
        $checker = new ArrayCapabilityChecker(['openmeta.manage']);
        $adapter = new UserAdapter($this->wp, $checker);

        $this->assertSame(5, $adapter->currentUserId());
        $this->assertTrue($adapter->can('openmeta.manage'));
    }
}
