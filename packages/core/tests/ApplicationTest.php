<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests;

use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Contracts\ApplicationInterface;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\ConfigRepositoryInterface;
use OpenMeta\Core\Contracts\KernelInterface;
use OpenMeta\Core\Kernel\KernelPhase;
use OpenMeta\Core\Tests\Fixtures\SmokeServiceProvider;

final class ApplicationTest extends CoreTestCase
{
    public function test_boot_returns_ready_application(): void
    {
        $app = Application::boot(
            ['app' => ['env' => 'testing']],
            [SmokeServiceProvider::class]
        );

        self::assertInstanceOf(ApplicationInterface::class, $app);
        self::assertTrue($app->isBooted());
        self::assertSame(Application::VERSION, $app->version());
        self::assertSame('testing', $app->config()->get('app.env'));
    }

    public function test_holds_container_config_and_kernel(): void
    {
        $app = Application::boot(['app' => ['env' => 'testing']]);

        self::assertInstanceOf(ContainerInterface::class, $app->container());
        self::assertInstanceOf(ConfigRepositoryInterface::class, $app->config());
        self::assertInstanceOf(KernelInterface::class, $app->kernel());
        self::assertSame(KernelPhase::Ready, $app->kernel()->phase());
    }

    public function test_application_is_bound_in_container(): void
    {
        $app = Application::boot();

        self::assertSame($app, $app->get(Application::class));
        self::assertTrue($app->has(ApplicationInterface::class));
    }

    public function test_step_methods_follow_bootstrap_order(): void
    {
        $app = new Application();

        $app->loadConfig(['app' => ['env' => 'testing']]);
        self::assertSame('testing', $app->config()->get('app.env'));

        $app->createContainer();
        self::assertInstanceOf(ContainerInterface::class, $app->container());

        $app->registerCoreServices();
        self::assertTrue($app->has('config'));
        self::assertTrue($app->has(Application::class));

        $app->registerProviders();
        $app->bootProviders();
        $app->ready();

        self::assertTrue($app->isBooted());
    }
}
