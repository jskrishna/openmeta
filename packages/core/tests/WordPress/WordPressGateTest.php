<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\WordPress;

use OpenMeta\Core\Tests\CoreTestCase;

/** Core is WP-free — compatibility N/A. */
final class WordPressGateTest extends CoreTestCase
{
    public function test_core_is_wordpress_agnostic(): void
    {
        $this->assertDirectoryDoesNotExist(dirname(__DIR__, 2) . '/src/Wordpress');
    }
}
