<?php

declare(strict_types=1);

/**
 * Bootstrap sequence smoke.
 *
 * Load Config → Create Container → Register Core Services
 * → Register Providers → Boot Providers → Application Ready
 *
 * Run: php packages/core/tests/Unit/bootstrap.php
 */

$root = dirname(__DIR__, 4);
require $root . '/vendor/autoload.php';

use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Bootstrap\Bootstrapper;
use OpenMeta\Core\Contracts\ApplicationInterface;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Kernel\KernelPhase;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Core\Tests\Fixtures\SmokeServiceProvider;

$seen = [];

$probe = new class ($seen) extends ServiceProvider {
    /** @param list<string> $seen */
    public function __construct(private array &$seen)
    {
    }

    public function register(ContainerInterface $container): void
    {
        $this->seen[] = 'register';
        assert($container->has('config') === true, 'Core services must exist before Register Providers');
        assert($container->has(Application::class) === true, 'Application must be bound as core service');
    }

    public function boot(ContainerInterface $container): void
    {
        $this->seen[] = 'boot';
    }
};

$app = Bootstrap::run(
    ['app' => ['env' => 'testing']],
    [$probe, SmokeServiceProvider::class]
);

assert($app instanceof ApplicationInterface);
assert($app->isBooted() === true, 'Application Ready');
assert($app->kernel()->phase() === KernelPhase::Ready, 'Kernel Ready');
assert($seen === ['register', 'boot'], 'Register then Boot');
assert($app->config()->get('app.env') === 'testing', 'Config loaded');
assert($app->get('smoke.framework_booted') === true, 'FrameworkBooted observed');

assert(Bootstrapper::boot() instanceof ApplicationInterface);
assert(Application::boot() instanceof ApplicationInterface);

fwrite(STDOUT, "OK Bootstrap — Load Config → … → Application Ready\n");
exit(0);
