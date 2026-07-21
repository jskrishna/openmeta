<?php

declare(strict_types=1);

namespace OpenMeta\Support;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Support\Filesystem\FilesystemInterface;
use OpenMeta\Support\Filesystem\LocalFilesystem;

/**
 * Registers Support factories. Most APIs are static and available via Composer autoload.
 */
final class SupportServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(FilesystemInterface::class, static fn (): LocalFilesystem => new LocalFilesystem());
        $container->alias(FilesystemInterface::class, LocalFilesystem::class);
        $container->alias(FilesystemInterface::class, 'filesystem');
    }

    public function boot(ContainerInterface $container): void
    {
    }
}
