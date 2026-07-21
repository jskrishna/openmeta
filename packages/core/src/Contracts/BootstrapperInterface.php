<?php

declare(strict_types=1);

namespace OpenMeta\Core\Contracts;

/**
 * Runs the bootstrap sequence and returns a ready application.
 *
 *   Load Config → Create Container → Register Core Services
 *   → Register Providers → Boot Providers → Application Ready
 *
 * Prefer {@see \OpenMeta\Core\Bootstrap\Bootstrap::run()}.
 */
interface BootstrapperInterface
{
    /**
     * @param array<string, mixed> $config
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     */
    public static function boot(array $config = [], array $providers = []): ApplicationInterface;
}
