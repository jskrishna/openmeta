<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Unit;

use OpenMeta\Admin\Layouts\LayoutManager;
use OpenMeta\Admin\Support\ScreenContext;
use OpenMeta\Admin\Tests\AdminTestCase;

final class LayoutManagerTest extends AdminTestCase
{
    public function test_renders_registered_layouts(): void
    {
        $layouts = new LayoutManager();
        $context = new ScreenContext('Title', 'Description', content: static fn (): string => '<p>Body</p>');

        $this->assertTrue($layouts->has('full-width'));
        $this->assertStringContainsString('Body', $layouts->render('full-width', $context));
        $this->assertStringContainsString('Description', $layouts->render('full-width', $context));
        $this->assertStringContainsString('om-layout--sidebar', $layouts->render('sidebar', $context));
    }
}
