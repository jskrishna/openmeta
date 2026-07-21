<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests;

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\ServiceProviderInterface;
use OpenMeta\Core\Kernel\Kernel;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Core\Tests\Fixtures\StringLog;

final class ProviderTest extends CoreTestCase
{
    public function test_provider_extends_base_and_implements_contract(): void
    {
        $provider = new class extends ServiceProvider {
        };

        self::assertInstanceOf(ServiceProviderInterface::class, $provider);
        self::assertInstanceOf(ServiceProvider::class, $provider);
    }

    public function test_register_runs_before_boot_for_all_providers(): void
    {
        $log = new StringLog();

        $providerA = new class ($log) extends ServiceProvider {
            public function __construct(private StringLog $log)
            {
            }

            public function register(ContainerInterface $container): void
            {
                $this->log->add('A:register');
                $container->bind('from.a', static fn (): string => 'a');
            }

            public function boot(ContainerInterface $container): void
            {
                $this->log->add('A:boot');
                \PHPUnit\Framework\Assert::assertTrue($container->has('from.b'));
            }
        };

        $providerB = new class ($log) extends ServiceProvider {
            public function __construct(private StringLog $log)
            {
            }

            public function register(ContainerInterface $container): void
            {
                $this->log->add('B:register');
                $container->bind('from.b', static fn (): string => 'b');
            }

            public function boot(ContainerInterface $container): void
            {
                $this->log->add('B:boot');
                \PHPUnit\Framework\Assert::assertSame('a', $container->get('from.a'));
            }
        };

        $kernel = new Kernel(new Container(), [$providerA, $providerB]);
        $kernel->run();

        self::assertSame(
            ['A:register', 'B:register', 'A:boot', 'B:boot'],
            $log->all()
        );
    }

    public function test_empty_base_provider_methods_are_safe(): void
    {
        $this->expectNotToPerformAssertions();

        $provider = new class extends ServiceProvider {
        };
        $container = new Container();

        $provider->register($container);
        $provider->boot($container);
    }
}
