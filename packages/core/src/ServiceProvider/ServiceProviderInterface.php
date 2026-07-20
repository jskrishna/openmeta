<?php

declare(strict_types=1);

namespace OpenMeta\Core\ServiceProvider;

use OpenMeta\Core\Container\Container;

/**
 * Service provider contract: register bindings, then boot.
 */
interface ServiceProviderInterface
{
    /**
     * Register bindings in the container.
     */
    public function register(Container $container): void;

    /**
     * Boot after every provider has registered.
     */
    public function boot(Container $container): void;
}
