<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\WordPress;

use OpenMeta\Validation\Tests\ValidationTestCase;
use OpenMeta\Validation\Validation;

/** Validation is pure PHP — WP N/A. */
final class WordPressGateTest extends ValidationTestCase
{
    public function test_validation_is_wordpress_agnostic(): void
    {
        $this->assertTrue(Validation::make(['n' => 'x'], ['n' => 'required'])->passes());
    }
}
