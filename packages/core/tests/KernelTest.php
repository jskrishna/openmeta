<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests;

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\KernelInterface;
use OpenMeta\Core\Exceptions\OpenMetaException;
use OpenMeta\Core\Kernel\Kernel;
use OpenMeta\Core\Kernel\KernelPhase;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Core\Tests\Fixtures\StringLog;

final class KernelTest extends CoreTestCase
{
    public function test_implements_kernel_interface(): void
    {
        $kernel = new Kernel(new Container());

        self::assertInstanceOf(KernelInterface::class, $kernel);
    }

    public function test_lifecycle_bootstrap_initialize_ready(): void
    {
        $log = new StringLog();

        $provider = new class ($log) extends ServiceProvider {
            public function __construct(private StringLog $log)
            {
            }

            public function register(ContainerInterface $container): void
            {
                $this->log->add('register');
            }

            public function boot(ContainerInterface $container): void
            {
                $this->log->add('boot');
            }
        };

        $kernel = new Kernel(new Container(), [$provider]);

        self::assertSame(KernelPhase::Pending, $kernel->phase());

        $kernel->bootstrap();
        self::assertSame(KernelPhase::Bootstrap, $kernel->phase());
        self::assertSame([], $log->all());

        $kernel->initialize();
        self::assertSame(KernelPhase::Initialize, $kernel->phase());
        self::assertSame(['register', 'boot'], $log->all());

        $kernel->ready();
        self::assertSame(KernelPhase::Ready, $kernel->phase());
        self::assertTrue($kernel->isReady());
        self::assertTrue($kernel->isBooted());
    }

    public function test_run_executes_full_lifecycle(): void
    {
        $kernel = new Kernel(new Container());
        $kernel->run();

        self::assertSame(KernelPhase::Ready, $kernel->phase());
    }

    public function test_cannot_add_providers_after_ready(): void
    {
        $provider = new class extends ServiceProvider {
        };
        $kernel = new Kernel(new Container(), [$provider]);
        $kernel->run();

        $this->expectException(OpenMetaException::class);
        $kernel->addProvider($provider);
    }

    public function test_providers_list_is_available(): void
    {
        $provider = new class extends ServiceProvider {
        };
        $kernel = new Kernel(new Container(), [$provider]);

        self::assertCount(1, $kernel->providers());
        self::assertSame($provider, $kernel->providers()[0]);
    }
}
