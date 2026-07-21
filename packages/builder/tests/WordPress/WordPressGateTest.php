<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\WordPress;

use OpenMeta\Builder\Application\BuilderApplication;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class WordPressGateTest extends BuilderTestCase
{
    public function test_admin_slot_registered_without_wp(): void
    {
        $this->assertTrue($this->screens->has(BuilderApplication::SCREEN_ID));
    }
}
