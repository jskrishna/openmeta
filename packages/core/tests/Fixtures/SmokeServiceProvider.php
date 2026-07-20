<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Fixtures;

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\ServiceProvider\ServiceProviderInterface;

/**
 * Minimal provider used by the core smoke script.
 */
final class SmokeServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->singleton('smoke.flag', static fn (): string => 'registered');
    }

    public function boot(Container $container): void
    {
        $container->instance('smoke.booted', new class {
            public string $value = 'booted';
        });
    }
}
