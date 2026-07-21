<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests\WordPress;

use OpenMeta\Support\Arr\Arr;
use OpenMeta\Support\Tests\SupportTestCase;

/** Support is pure PHP — WP N/A. */
final class WordPressGateTest extends SupportTestCase
{
    public function test_support_is_wordpress_agnostic(): void
    {
        $this->assertSame('ok', Arr::get(['a' => 'ok'], 'a'));
    }
}
