<?php

declare(strict_types=1);

namespace OpenMeta\Core\Kernel;

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\ServiceProvider\ServiceProviderInterface;
use RuntimeException;

/**
 * Registers then boots service providers.
 */
final class Kernel
{
    /** @var list<ServiceProviderInterface> */
    private array $providers = [];

    private bool $booted = false;

    /**
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     */
    public function __construct(
        private readonly Container $container,
        array $providers = [],
    ) {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * @param class-string<ServiceProviderInterface>|ServiceProviderInterface $provider
     */
    public function addProvider(ServiceProviderInterface|string $provider): void
    {
        if ($this->booted) {
            throw new RuntimeException('Cannot add service providers after the kernel has booted.');
        }

        if (is_string($provider)) {
            if (! is_a($provider, ServiceProviderInterface::class, true)) {
                throw new RuntimeException(sprintf(
                    'Provider [%s] must implement %s.',
                    $provider,
                    ServiceProviderInterface::class
                ));
            }

            $provider = new $provider();
        }

        $this->providers[] = $provider;
    }

    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        foreach ($this->providers as $provider) {
            $provider->register($this->container);
        }

        foreach ($this->providers as $provider) {
            $provider->boot($this->container);
        }

        $this->booted = true;
    }

    public function isBooted(): bool
    {
        return $this->booted;
    }

    public function container(): Container
    {
        return $this->container;
    }
}
