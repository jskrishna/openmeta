<?php

declare(strict_types=1);

namespace OpenMeta\Core\Contracts;

/**
 * Extension unit for the framework.
 *
 * Lifecycle (Kernel-enforced):
 *
 *   Register  →  bind services into the container
 *       ↓
 *   Boot      →  start / wire services after every provider has registered
 *
 * Every future OpenMeta package plugs in by implementing this contract
 * (or extending {@see \OpenMeta\Core\Providers\ServiceProvider}) and
 * passing the provider to {@see KernelInterface::addProvider()} /
 * {@see \OpenMeta\Core\Application\Application::boot()}.
 */
interface ServiceProviderInterface
{
    /**
     * Bind services, contracts, and factories.
     *
     * Do not resolve other providers' bindings here — they may not exist yet.
     */
    public function register(ContainerInterface $container): void;

    /**
     * Start services after all providers have finished register().
     *
     * Safe to resolve container bindings and wire listeners.
     */
    public function boot(ContainerInterface $container): void;
}
