<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests;

use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Bootstrap\Bootstrapper;
use OpenMeta\Core\Contracts\ApplicationInterface;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Kernel\KernelPhase;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Core\Tests\Fixtures\SmokeServiceProvider;
use OpenMeta\Core\Tests\Fixtures\StringLog;

final class BootstrapTest extends CoreTestCase
{
    public function test_run_follows_bootstrap_sequence(): void
    {
        $seen = new StringLog();

        $probe = new class ($seen) extends ServiceProvider {
            public function __construct(private StringLog $seen)
            {
            }

            public function register(ContainerInterface $container): void
            {
                $this->seen->add('register');
                \PHPUnit\Framework\Assert::assertTrue($container->has('config'));
                \PHPUnit\Framework\Assert::assertTrue($container->has(Application::class));
            }

            public function boot(ContainerInterface $container): void
            {
                $this->seen->add('boot');
            }
        };

        $app = Bootstrap::run(
            ['app' => ['env' => 'testing']],
            [$probe, SmokeServiceProvider::class]
        );

        self::assertInstanceOf(ApplicationInterface::class, $app);
        self::assertTrue($app->isBooted());
        self::assertSame(KernelPhase::Ready, $app->kernel()->phase());
        self::assertSame(['register', 'boot'], $seen->all());
        self::assertSame('testing', $app->config()->get('app.env'));
        self::assertTrue($app->get('smoke.framework_booted'));
    }

    public function test_aliases_delegate_to_bootstrap_run(): void
    {
        self::assertInstanceOf(ApplicationInterface::class, Bootstrapper::boot());
        self::assertInstanceOf(ApplicationInterface::class, Application::boot());
        self::assertInstanceOf(ApplicationInterface::class, Bootstrap::init());
    }
}
