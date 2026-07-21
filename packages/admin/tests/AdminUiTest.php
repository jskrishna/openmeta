<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests;

use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Exceptions\AdminException;
use OpenMeta\Admin\Forms\AdminForm;
use OpenMeta\Admin\Tables\AdminTable;
use OpenMeta\Security\Permissions\Permission;

final class AdminUiTest extends AdminTestCase
{
    public function test_menus_dashboard_and_settings_registered(): void
    {
        self::assertTrue($this->admin->menus()->has('openmeta'));
        self::assertTrue($this->admin->menus()->has('openmeta-settings'));
        self::assertTrue($this->admin->screens()->has(Dashboard::SCREEN_ID));
        self::assertTrue($this->admin->settings()->has('general'));
        self::assertContains('health', $this->admin->dashboard()->widgetIds());
    }

    public function test_dashboard_requires_capability(): void
    {
        $this->expectException(AdminException::class);
        $this->admin->renderScreen(Dashboard::SCREEN_ID);
    }

    public function test_render_dashboard_and_entries_table(): void
    {
        $this->grant('manage_options', 'edit_posts');

        $html = $this->admin->renderScreen(Dashboard::SCREEN_ID);
        self::assertStringContainsString('Welcome to OpenMeta.', $html);
        self::assertStringContainsString('om-theme', $html);

        $entries = $this->admin->renderScreen('openmeta-entries');
        self::assertStringContainsString('Demo Entry', $entries);
        self::assertStringContainsString('om-table', $entries);
    }

    public function test_admin_form_nonce_and_validation(): void
    {
        $form = new AdminForm(
            'demo-form',
            [['name' => 'title', 'label' => 'Title', 'rules' => 'required|string|min:3']],
            $this->nonce,
            ['title' => '']
        );

        $html = $form->render();
        self::assertStringContainsString('name="title"', $html);
        self::assertStringContainsString('_wpnonce', $html);

        $fail = $form->submit(['title' => 'ab', '_wpnonce' => 'bad']);
        self::assertFalse($fail['ok']);

        $token = $this->nonce->create('demo-form');
        $failValidation = $form->submit(['title' => 'ab', '_wpnonce' => $token]);
        self::assertFalse($failValidation['ok']);

        $ok = $form->submit(['title' => 'Hello', '_wpnonce' => $this->nonce->create('demo-form')]);
        self::assertTrue($ok['ok']);
        self::assertSame('Hello', $ok['values']['title']);
    }

    public function test_settings_store_and_table_pagination(): void
    {
        $this->admin->store()->set('site_name', 'OpenMeta Site');
        self::assertSame('OpenMeta Site', $this->admin->store()->get('site_name'));

        $table = new AdminTable(['A'], [['1'], ['2'], ['3']], 2, 2);
        self::assertSame(3, $table->total());
        self::assertStringContainsString('3', $table->render());
        self::assertStringNotContainsString('>1<', $table->render());
    }

    public function test_settings_screen_permission(): void
    {
        $this->grant('manage_options');
        $html = $this->admin->renderScreen('openmeta-settings');
        self::assertStringContainsString('Site name', $html);
        self::assertTrue($this->gate->can(Permission::MANAGE));
    }
}
