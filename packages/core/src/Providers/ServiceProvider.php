<?php

declare(strict_types=1);

namespace OpenMeta\Core\Providers;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\ServiceProviderInterface;

/**
 * Base service provider for packages.
 *
 * Lifecycle:
 *
 *   Register
 *       ↓
 *     Boot
 *
 * Extend this class in support, database, fields, api, ui, admin, builder, etc.
 * Override only the phases you need.
 */
abstract class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Bind services into the container.
     *
     * All providers' register() methods run before any boot() method.
     */
    public function register(ContainerInterface $container): void
    {
    }

    /**
     * Boot services after the full container graph is registered.
     */
    public function boot(ContainerInterface $container): void
    {
    }
}
