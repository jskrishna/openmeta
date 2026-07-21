<?php

declare(strict_types=1);

namespace OpenMeta\Core\Contracts;

use OpenMeta\Core\Kernel\KernelPhase;

/**
 * Kernel — lifecycle manager only.
 *
 *   Bootstrap
 *       ↓
 *   Initialize
 *       ↓
 *   Ready
 *
 * No WordPress-specific logic.
 */
interface KernelInterface
{
    /**
     * @param class-string<ServiceProviderInterface>|ServiceProviderInterface $provider
     */
    public function addProvider(ServiceProviderInterface|string $provider): void;

    /**
     * Phase 1 — prepare the kernel (container attached, providers accepted).
     */
    public function bootstrap(): void;

    /**
     * Phase 2 — register then boot all service providers.
     */
    public function initialize(): void;

    /**
     * Phase 3 — mark the kernel ready for use.
     */
    public function ready(): void;

    /**
     * Run the full lifecycle: Bootstrap → Initialize → Ready.
     */
    public function run(): void;

    /**
     * @deprecated Use {@see run()} — kept as an alias.
     */
    public function boot(): void;

    /**
     * Provider phase inside Initialize — register all providers.
     */
    public function registerProviders(): void;

    /**
     * Provider phase inside Initialize — boot all providers.
     */
    public function bootProviders(): void;

    public function phase(): KernelPhase;

    public function isReady(): bool;

    /**
     * @deprecated Use {@see isReady()}
     */
    public function isBooted(): bool;

    public function container(): ContainerInterface;

    /**
     * @return list<ServiceProviderInterface>
     */
    public function providers(): array;
}
