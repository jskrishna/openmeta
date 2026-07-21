<?php

declare(strict_types=1);

namespace OpenMeta\Core\Bootstrap;

use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Contracts\ServiceProviderInterface;

/**
 * Framework bootstrap sequence (no WordPress logic).
 *
 *   Load Config
 *       ↓
 *   Create Container
 *       ↓
 *   Register Core Services
 *       ↓
 *   Register Providers
 *       ↓
 *   Boot Providers
 *       ↓
 *   Application Ready
 */
final class Bootstrap
{
    public const VERSION = Application::VERSION;

    /**
     * Run the full bootstrap sequence and return a ready Application.
     *
     * @param array<string, mixed> $config Runtime overrides merged over default config files
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     */
    public static function run(array $config = [], array $providers = []): Application
    {
        $app = new Application();

        // 1) Load Config
        $app->loadConfig($config);

        // 2) Create Container
        $app->createContainer();

        // 3) Register Core Services
        $app->registerCoreServices($providers);

        // 4) Register Providers
        $app->registerProviders();

        // 5) Boot Providers
        $app->bootProviders();

        // 6) Application Ready
        $app->ready();

        return $app;
    }

    /**
     * @param array<string, mixed> $config
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     *
     * @deprecated Use {@see run()}
     */
    public static function init(array $config = [], array $providers = []): Application
    {
        return self::run($config, $providers);
    }
}
