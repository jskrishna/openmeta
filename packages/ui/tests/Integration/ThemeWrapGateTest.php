<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Tests\Integration;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Security\SecurityServiceProvider;
use OpenMeta\Ui\Theme\Theme;
use OpenMeta\Ui\UiServiceProvider;
use PHPUnit\Framework\TestCase;

final class ThemeWrapGateTest extends TestCase
{
    public function test_theme_wraps_html(): void
    {
        $app = Bootstrap::run(
            ['app' => ['key' => 'phase12-ui']],
            [SecurityServiceProvider::class, UiServiceProvider::class]
        );
        /** @var Theme $theme */
        $theme = $app->get(Theme::class);
        $html = $theme->wrap('<p>Hi</p>');
        $this->assertStringContainsString('Hi', $html);
    }
}
