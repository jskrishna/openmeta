<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Fixtures;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Events\EventDispatcherInterface;
use OpenMeta\Core\Events\FrameworkBooted;
use OpenMeta\Core\Providers\ServiceProvider;

/**
 * Smoke provider for v0.1.0-alpha bootstrap.
 */
final class SmokeServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton('smoke.flag', static fn (): string => 'registered');
        $container->singleton('smoke.framework_booted', static fn (): bool => false);
    }

    public function boot(ContainerInterface $container): void
    {
        $container->instance('smoke.booted', new class {
            public string $value = 'booted';
        });

        /** @var EventDispatcherInterface $events */
        $events = $container->get(EventDispatcherInterface::class);
        $events->listen(FrameworkBooted::class, static function () use ($container): void {
            $container->singleton('smoke.framework_booted', static fn (): bool => true);
        });
    }
}
