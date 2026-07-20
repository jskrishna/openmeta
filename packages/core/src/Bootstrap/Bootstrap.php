<?php

declare(strict_types=1);

namespace OpenMeta\Core\Bootstrap;

use OpenMeta\Core\Config\Repository;
use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Kernel\Kernel;
use OpenMeta\Core\ServiceProvider\ServiceProviderInterface;

/**
 * Application entrypoint for Milestone v0.1.
 *
 * Flow: Bootstrap → Container → Config → Kernel → Service Provider → Done
 */
final class Bootstrap
{
    public const VERSION = '0.1.0-alpha';

    /**
     * @param array<string, mixed> $config
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     */
    public static function init(array $config = [], array $providers = []): Kernel
    {
        $container = new Container();
        $repository = new Repository($config);

        $container->instance(Container::class, $container);
        $container->instance(Repository::class, $repository);
        $container->singleton('config', static fn (): Repository => $repository);

        $kernel = new Kernel($container, $providers);
        $container->instance(Kernel::class, $kernel);

        $kernel->boot();

        return $kernel;
    }
}
