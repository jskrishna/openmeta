<?php

declare(strict_types=1);

namespace OpenMeta\Core\Bootstrap;

use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Contracts\ApplicationInterface;
use OpenMeta\Core\Contracts\BootstrapperInterface;
use OpenMeta\Core\Contracts\ServiceProviderInterface;

/**
 * Alias of {@see Bootstrap::run()} for the bootstrap sequence.
 *
 *   Load Config → Create Container → Register Core Services
 *   → Register Providers → Boot Providers → Application Ready
 */
final class Bootstrapper implements BootstrapperInterface
{
    public const VERSION = Application::VERSION;

    public static function defaultConfigPath(): string
    {
        return Application::defaultConfigPath();
    }

    /**
     * @param array<string, mixed> $config Runtime overrides merged over default config files
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     */
    public static function boot(array $config = [], array $providers = []): ApplicationInterface
    {
        return Bootstrap::run($config, $providers);
    }
}
