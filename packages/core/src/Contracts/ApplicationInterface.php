<?php

declare(strict_types=1);

namespace OpenMeta\Core\Contracts;

/**
 * Framework main entry point and ready-application façade.
 *
 * Responsibilities:
 * - Hold Container
 * - Load Config
 * - Start Kernel
 * - Register Providers
 * - Boot Providers
 */
interface ApplicationInterface
{
    public function version(): string;

    public function container(): ContainerInterface;

    public function kernel(): KernelInterface;

    public function config(): ConfigRepositoryInterface;

    public function events(): EventDispatcherInterface;

    public function isBooted(): bool;

    public function get(string $id): mixed;

    public function has(string $id): bool;
}
