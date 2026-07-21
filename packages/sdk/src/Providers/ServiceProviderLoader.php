<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Providers;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\ServiceProviderInterface;
use OpenMeta\Sdk\Contracts\ServiceProviderLoaderInterface;
use OpenMeta\Sdk\Exceptions\SdkException;

/**
 * Instantiates, registers, and boots extension service providers at runtime.
 *
 * Because extensions activate after the kernel is Ready (when providers can no
 * longer be added to the kernel), the loader drives register()/boot() directly
 * against the container.
 */
final class ServiceProviderLoader implements ServiceProviderLoaderInterface
{
    /** @var array<string, true> */
    private array $loaded = [];

    public function load(array $providers, ContainerInterface $container): void
    {
        foreach ($providers as $providerClass) {
            if (isset($this->loaded[$providerClass])) {
                continue;
            }

            $provider = $this->instantiate($providerClass);
            $provider->register($container);
            $provider->boot($container);

            $this->loaded[$providerClass] = true;
        }
    }

    public function isLoaded(string $provider): bool
    {
        return isset($this->loaded[$provider]);
    }

    /**
     * @param class-string $providerClass
     */
    private function instantiate(string $providerClass): ServiceProviderInterface
    {
        if (! class_exists($providerClass)) {
            throw new SdkException(sprintf('Service provider [%s] does not exist.', $providerClass));
        }

        $provider = new $providerClass();

        if (! $provider instanceof ServiceProviderInterface) {
            throw new SdkException(sprintf(
                'Service provider [%s] must implement %s.',
                $providerClass,
                ServiceProviderInterface::class,
            ));
        }

        return $provider;
    }
}
