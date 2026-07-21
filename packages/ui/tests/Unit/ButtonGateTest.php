<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Tests\Unit;

use OpenMeta\Ui\Primitives\Button;
use PHPUnit\Framework\TestCase;

final class ButtonGateTest extends TestCase
{
    public function test_button_renders_label(): void
    {
        $html = Button::render('Save');
        $this->assertStringContainsString('Save', $html);
    }
}
