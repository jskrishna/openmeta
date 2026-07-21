<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Unit;

use OpenMeta\Wordpress\Plugin\Requirements;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class RequirementsGateTest extends WordpressTestCase
{
    public function test_php_requirement(): void
    {
        $this->assertTrue((new Requirements())->passes('8.3.0', '6.4.0'));
        $this->assertFalse((new Requirements())->passes('8.2.0', '6.4.0'));
    }
}
