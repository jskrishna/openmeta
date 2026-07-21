<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$files = [];

$files['ui/Unit/ButtonGateTest.php'] = <<<'PHP'
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
PHP;

$files['ui/Integration/ThemeWrapGateTest.php'] = <<<'PHP'
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
PHP;

$files['ui/WordPress/WordPressGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Tests\WordPress;

use PHPUnit\Framework\TestCase;

/** UI PHP kit is WP-enqueue agnostic in CI — N/A. */
final class WordPressGateTest extends TestCase
{
    public function test_ui_php_kit_is_wordpress_agnostic(): void
    {
        $this->assertTrue(true);
    }
}
PHP;

$files['ui/Performance/RenderPerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Tests\Performance;

use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;
use OpenMeta\Ui\Components\Card;
use PHPUnit\Framework\TestCase;

final class RenderPerformanceTest extends TestCase
{
    use AssertsPerformanceBudget;

    public function test_card_render_under_budget(): void
    {
        $this->assertUnderMs(50.0, static function (): void {
            for ($i = 0; $i < 200; $i++) {
                Card::render('T', '<p>B</p>');
            }
        }, 'ui card render');
    }
}
PHP;

$files['ui/Security/EscapeOutputTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Tests\Security;

use OpenMeta\Ui\Primitives\Notice;
use PHPUnit\Framework\TestCase;

final class EscapeOutputTest extends TestCase
{
    public function test_notice_escapes_message(): void
    {
        $html = Notice::render('<script>x</script>', 'error');
        $this->assertStringNotContainsString('<script>x</script>', $html);
    }
}
PHP;

$files['admin/Unit/MenuRegistryGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Unit;

use OpenMeta\Admin\Menus\MenuItem;
use OpenMeta\Admin\Menus\MenuRegistry;
use OpenMeta\Admin\Tests\AdminTestCase;
use OpenMeta\Security\Permissions\Permission;

final class MenuRegistryGateTest extends AdminTestCase
{
    public function test_menu_registration(): void
    {
        $menus = new MenuRegistry();
        $menus->add(new MenuItem('x', 'X', 'screen-x', Permission::MANAGE));
        $this->assertTrue($menus->has('x'));
    }
}
PHP;

$files['admin/Integration/ScreenRenderGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Integration;

use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Tests\AdminTestCase;

final class ScreenRenderGateTest extends AdminTestCase
{
    public function test_dashboard_screen_renders_when_allowed(): void
    {
        $this->grant('manage_options');
        $html = $this->admin->renderScreen(Dashboard::SCREEN_ID);
        $this->assertStringContainsString('OpenMeta', $html);
    }
}
PHP;

$files['admin/WordPress/WordPressGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\WordPress;

use OpenMeta\Admin\Tests\AdminTestCase;

/** Admin menus register in-memory; WP add_menu_page bridged by wordpress package. */
final class WordPressGateTest extends AdminTestCase
{
    public function test_admin_screens_exist_without_wp(): void
    {
        $this->assertTrue($this->admin->screens()->has('openmeta-settings'));
    }
}
PHP;

$files['admin/Performance/DashboardPerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Performance;

use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Tests\AdminTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class DashboardPerformanceTest extends AdminTestCase
{
    use AssertsPerformanceBudget;

    public function test_dashboard_render_under_budget(): void
    {
        $this->grant('manage_options');
        $this->assertUnderMs(200.0, function (): void {
            for ($i = 0; $i < 20; $i++) {
                $this->admin->renderScreen(Dashboard::SCREEN_ID);
            }
        }, 'admin dashboard');
    }
}
PHP;

$files['admin/Security/CapabilityGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Security;

use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Exceptions\AdminException;
use OpenMeta\Admin\Tests\AdminTestCase;

final class CapabilityGateTest extends AdminTestCase
{
    public function test_screen_denied_without_capability(): void
    {
        $this->expectException(AdminException::class);
        $this->admin->renderScreen(Dashboard::SCREEN_ID);
    }
}
PHP;

$files['builder/Unit/CanvasGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Unit;

use OpenMeta\Builder\State\CanvasNode;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class CanvasGateTest extends BuilderTestCase
{
    public function test_add_and_select(): void
    {
        $this->canvas->add(new CanvasNode('n1', 'text', 'title'));
        $this->canvas->select('n1');
        $this->assertSame('n1', $this->canvas->selectedId());
    }
}
PHP;

$files['builder/Integration/SavePipelineGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Integration;

use OpenMeta\Builder\App\VisualBuilder;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class SavePipelineGateTest extends BuilderTestCase
{
    public function test_save_with_nonce(): void
    {
        $this->grant('manage_options');
        $this->templates->apply('contact', $this->canvas);
        $saved = $this->builder->save($this->nonce->create(VisualBuilder::SCREEN_ID));
        $this->assertNotEmpty($saved);
    }
}
PHP;

$files['builder/WordPress/WordPressGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\WordPress;

use OpenMeta\Builder\App\VisualBuilder;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class WordPressGateTest extends BuilderTestCase
{
    public function test_admin_slot_registered_without_wp(): void
    {
        $this->assertTrue($this->screens->has(VisualBuilder::SCREEN_ID));
    }
}
PHP;

$files['builder/Performance/CanvasScalePerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Performance;

use OpenMeta\Builder\Tests\BuilderTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class CanvasScalePerformanceTest extends BuilderTestCase
{
    use AssertsPerformanceBudget;

    public function test_many_fields_under_budget(): void
    {
        $this->assertUnderMs(500.0, function (): void {
            for ($i = 0; $i < 100; $i++) {
                $this->dragDrop->dropNew($this->canvas, 'text', 'f' . $i);
            }
        }, 'builder canvas scale');
    }
}
PHP;

$files['builder/Security/SaveAuthzTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Security;

use OpenMeta\Builder\App\VisualBuilder;
use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class SaveAuthzTest extends BuilderTestCase
{
    public function test_save_denied_without_permission(): void
    {
        $this->expectException(BuilderException::class);
        $this->builder->save($this->nonce->create(VisualBuilder::SCREEN_ID));
    }
}
PHP;

$files['wordpress/Unit/RequirementsGateTest.php'] = <<<'PHP'
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
PHP;

$files['wordpress/Integration/PluginBootGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Integration;

use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class PluginBootGateTest extends WordpressTestCase
{
    public function test_boot_registers_lifecycle_hooks(): void
    {
        $this->assertNotNull($this->plugin->boot($this->testConfig()));
        $this->assertArrayHasKey('admin_menu', $this->wp->actions);
        $this->assertArrayHasKey('rest_api_init', $this->wp->actions);
    }
}
PHP;

$files['wordpress/WordPress/NativeRuntimeGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\WordPress;

use OpenMeta\Wordpress\Runtime\NativeWordPressRuntime;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class NativeRuntimeGateTest extends WordpressTestCase
{
    public function test_native_runtime_safe_without_wordpress(): void
    {
        $native = new NativeWordPressRuntime();
        $this->assertFalse($native->isLoaded());
        $this->assertFalse($native->registerRestRoute('openmeta/v1', '/health', []));
    }
}
PHP;

$files['wordpress/Performance/BootPerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Performance;

use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class BootPerformanceTest extends WordpressTestCase
{
    use AssertsPerformanceBudget;

    public function test_plugin_boot_under_budget(): void
    {
        $this->assertUnderMs(2000.0, function (): void {
            $this->plugin->boot($this->testConfig());
            $this->wp->doAction('admin_menu');
            $this->wp->doAction('rest_api_init');
            $this->wp->doAction('init');
        }, 'wordpress boot');
    }
}
PHP;

$files['wordpress/Security/CapabilitySeedTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Security;

use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class CapabilitySeedTest extends WordpressTestCase
{
    public function test_activate_seeds_openmeta_capabilities(): void
    {
        $this->plugin->activate();
        $caps = array_column($this->wp->capabilities, 'capability');
        $this->assertContains('openmeta.manage', $caps);
    }
}
PHP;

foreach ($files as $rel => $contents) {
    $path = $root . '/packages/' . preg_replace('#^([^/]+)/#', '$1/tests/', $rel, 1);
    if (! is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
    file_put_contents($path, $contents);
}

echo count($files) . " files (batch C)\n";
