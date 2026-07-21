<?php

declare(strict_types=1);

namespace OpenMeta\Core\Kernel;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\KernelInterface;
use OpenMeta\Core\Contracts\ServiceProviderInterface;
use OpenMeta\Core\Exceptions\OpenMetaException;

/**
 * Kernel — manages framework lifecycle only.
 *
 *   Bootstrap
 *       ↓
 *   Initialize   (Register → Boot providers)
 *       ↓
 *   Ready
 *
 * No WordPress-specific logic.
 */
final class Kernel implements KernelInterface
{
    /** @var list<ServiceProviderInterface> */
    private array $providers = [];

    private KernelPhase $phase = KernelPhase::Pending;

    private bool $providersRegistered = false;

    private bool $providersBooted = false;

    /**
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     */
    public function __construct(
        private readonly ContainerInterface $container,
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
        if ($this->phase !== KernelPhase::Pending && $this->phase !== KernelPhase::Bootstrap) {
            throw new OpenMetaException(
                'Cannot add service providers after Initialize has started.'
            );
        }

        if (is_string($provider)) {
            if (! class_exists($provider)) {
                throw new OpenMetaException(sprintf(
                    'Provider [%s] does not exist.',
                    $provider
                ));
            }

            $instance = new $provider();

            if (! $instance instanceof ServiceProviderInterface) {
                throw new OpenMetaException(sprintf(
                    'Provider [%s] must implement %s.',
                    $provider,
                    ServiceProviderInterface::class
                ));
            }

            $provider = $instance;
        }

        $this->providers[] = $provider;
    }

    /**
     * Bootstrap — prepare lifecycle; no domain or WordPress work.
     */
    public function bootstrap(): void
    {
        if ($this->phase !== KernelPhase::Pending) {
            return;
        }

        $this->phase = KernelPhase::Bootstrap;
    }

    /**
     * Initialize — run provider Register → Boot.
     */
    public function initialize(): void
    {
        if ($this->phase === KernelPhase::Initialize || $this->phase === KernelPhase::Ready) {
            return;
        }

        if ($this->phase === KernelPhase::Pending) {
            $this->bootstrap();
        }

        $this->registerProviders();
        $this->bootProviders();

        $this->phase = KernelPhase::Initialize;
    }

    /**
     * Ready — kernel lifecycle complete; safe for Application use.
     */
    public function ready(): void
    {
        if ($this->phase === KernelPhase::Ready) {
            return;
        }

        if ($this->phase !== KernelPhase::Initialize) {
            $this->initialize();
        }

        $this->phase = KernelPhase::Ready;
    }

    /**
     * Full lifecycle: Bootstrap → Initialize → Ready.
     */
    public function run(): void
    {
        $this->bootstrap();
        $this->initialize();
        $this->ready();
    }

    /**
     * @deprecated Use {@see run()}
     */
    public function boot(): void
    {
        $this->run();
    }

    public function registerProviders(): void
    {
        if ($this->providersRegistered) {
            return;
        }

        if ($this->phase === KernelPhase::Pending) {
            $this->bootstrap();
        }

        if ($this->phase === KernelPhase::Ready) {
            throw new OpenMetaException('Cannot register providers after the kernel is Ready.');
        }

        foreach ($this->providers as $provider) {
            $provider->register($this->container);
        }

        $this->providersRegistered = true;
    }

    public function bootProviders(): void
    {
        if ($this->providersBooted) {
            return;
        }

        if (! $this->providersRegistered) {
            $this->registerProviders();
        }

        if ($this->phase === KernelPhase::Ready) {
            throw new OpenMetaException('Cannot boot providers after the kernel is Ready.');
        }

        foreach ($this->providers as $provider) {
            $provider->boot($this->container);
        }

        $this->providersBooted = true;

        if ($this->phase === KernelPhase::Bootstrap) {
            $this->phase = KernelPhase::Initialize;
        }
    }

    public function phase(): KernelPhase
    {
        return $this->phase;
    }

    public function isReady(): bool
    {
        return $this->phase === KernelPhase::Ready;
    }

    /**
     * @deprecated Use {@see isReady()}
     */
    public function isBooted(): bool
    {
        return $this->isReady();
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return list<ServiceProviderInterface>
     */
    public function providers(): array
    {
        return $this->providers;
    }
}
