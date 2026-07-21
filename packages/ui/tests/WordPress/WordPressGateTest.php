<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Tests\WordPress;

use OpenMeta\Ui\Primitives\Button;
use PHPUnit\Framework\TestCase;

/** UI PHP kit is WP-enqueue agnostic in CI — N/A. */
final class WordPressGateTest extends TestCase
{
    public function test_ui_php_kit_is_wordpress_agnostic(): void
    {
        $this->assertStringContainsString('Save', Button::render('Save'));
    }
}
