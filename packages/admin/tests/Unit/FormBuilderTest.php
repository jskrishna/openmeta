<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Unit;

use OpenMeta\Admin\Events\FormSubmitted;
use OpenMeta\Admin\Tests\AdminTestCase;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Validation\ValidationServiceProvider;
use OpenMeta\Security\SecurityServiceProvider;
use OpenMeta\Ui\UiServiceProvider;
use OpenMeta\Admin\AdminServiceProvider;

final class FormBuilderTest extends AdminTestCase
{
    public function test_form_builder_sections_and_submit_event(): void
    {
        $app = Bootstrap::run(
            ['app' => ['key' => 'admin-test-secret']],
            [
                ValidationServiceProvider::class,
                SecurityServiceProvider::class,
                UiServiceProvider::class,
                AdminServiceProvider::class,
            ]
        );

        $fired = false;
        $dispatcher = $app->get(EventDispatcherInterface::class);
        $dispatcher->listen(FormSubmitted::class, static function (FormSubmitted $event) use (&$fired): void {
            $fired = $event->success;
        });

        $form = $app->get(\OpenMeta\Admin\Application\AdminApplication::class)
            ->form('builder-demo')
            ->section('main', 'Main')
            ->group('main', 'default')
            ->field('main', 'default', [
                'name' => 'title',
                'label' => 'Title',
                'rules' => 'required|string|min:2',
            ]);

        $html = $form->render(['title' => 'Hello']);
        $this->assertStringContainsString('om-form__section', $html);

        $nonce = $app->get(\OpenMeta\Security\Nonce\Nonce::class);
        $result = $form->submit(['title' => 'OK', '_wpnonce' => $nonce->create('builder-demo')]);
        $this->assertTrue($result['ok']);
        $this->assertTrue($fired);
    }
}
