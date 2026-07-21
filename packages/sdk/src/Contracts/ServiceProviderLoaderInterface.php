<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Contracts;

use OpenMeta\Core\Contracts\ContainerInterface;

/**
 * Loads an extension's service providers into the running container.
 */
interface ServiceProviderLoaderInterface
{
    /**
     * Register then boot each provider. Providers already loaded are skipped.
     *
     * @param list<class-string> $providers
     */
    public function load(array $providers, ContainerInterface $container): void;

    public function isLoaded(string $provider): bool;
}
