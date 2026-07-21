<?php

declare(strict_types=1);

namespace OpenMeta\Sdk;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Sdk\Bootstrap\ExtensionBootstrapper;
use OpenMeta\Sdk\Compatibility\CompatibilityChecker;
use OpenMeta\Sdk\Contracts\CompatibilityCheckerInterface;
use OpenMeta\Sdk\Contracts\DependencyResolverInterface;
use OpenMeta\Sdk\Contracts\DiscoveryInterface;
use OpenMeta\Sdk\Contracts\ExtensionManagerInterface;
use OpenMeta\Sdk\Contracts\ExtensionRegistryInterface;
use OpenMeta\Sdk\Contracts\FeatureFlagsInterface;
use OpenMeta\Sdk\Contracts\LifecycleManagerInterface;
use OpenMeta\Sdk\Contracts\ResourceLoaderInterface;
use OpenMeta\Sdk\Contracts\ServiceProviderLoaderInterface;
use OpenMeta\Sdk\Contracts\VersionComparatorInterface;
use OpenMeta\Sdk\Discovery\ManualDiscovery;
use OpenMeta\Sdk\Lifecycle\LifecycleManager;
use OpenMeta\Sdk\Manager\ExtensionManager;
use OpenMeta\Sdk\Manifest\ManifestFactory;
use OpenMeta\Sdk\Providers\ServiceProviderLoader;
use OpenMeta\Sdk\Registry\ExtensionRegistry;
use OpenMeta\Sdk\Resources\ResourceLoader;
use OpenMeta\Sdk\Support\DependencyResolver;
use OpenMeta\Sdk\Support\FeatureFlags;
use OpenMeta\Sdk\Versioning\VersionComparator;
use OpenMeta\Sdk\Versioning\VersionManager;

/**
 * Registers the Extension SDK services into the container.
 *
 * Public surface (Manager, Registry, Manifest factory, Discovery, Lifecycle)
 * is bound to its interface; supporting collaborators are bound to concretes.
 */
final class SdkServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $this->registerVersioning($container);
        $this->registerManifest($container);
        $this->registerRegistryAndResources($container);
        $this->registerResolution($container);
        $this->registerLifecycle($container);
        $this->registerManager($container);
    }

    private function registerVersioning(ContainerInterface $container): void
    {
        $container->singleton(VersionComparator::class, static fn (): VersionComparator => new VersionComparator());
        $container->alias(VersionComparator::class, VersionComparatorInterface::class);

        $container->singleton(VersionManager::class, static function (ContainerInterface $c): VersionManager {
            return new VersionManager($c->get(VersionComparatorInterface::class));
        });
    }

    private function registerManifest(ContainerInterface $container): void
    {
        $container->singleton(ManifestFactory::class, static fn (): ManifestFactory => new ManifestFactory());
    }

    private function registerRegistryAndResources(ContainerInterface $container): void
    {
        $container->singleton(ExtensionRegistry::class, static fn (): ExtensionRegistry => new ExtensionRegistry());
        $container->alias(ExtensionRegistry::class, ExtensionRegistryInterface::class);

        $container->singleton(ResourceLoader::class, static fn (): ResourceLoader => new ResourceLoader());
        $container->alias(ResourceLoader::class, ResourceLoaderInterface::class);

        $container->singleton(FeatureFlags::class, static fn (): FeatureFlags => new FeatureFlags());
        $container->alias(FeatureFlags::class, FeatureFlagsInterface::class);
    }

    private function registerResolution(ContainerInterface $container): void
    {
        $container->singleton(DependencyResolver::class, static fn (): DependencyResolver => new DependencyResolver());
        $container->alias(DependencyResolver::class, DependencyResolverInterface::class);

        $container->singleton(
            CompatibilityChecker::class,
            static fn (ContainerInterface $c): CompatibilityChecker => new CompatibilityChecker(
                $c->get(VersionComparatorInterface::class),
            ),
        );
        $container->alias(CompatibilityChecker::class, CompatibilityCheckerInterface::class);

        $container->singleton(
            ServiceProviderLoader::class,
            static fn (): ServiceProviderLoader => new ServiceProviderLoader(),
        );
        $container->alias(ServiceProviderLoader::class, ServiceProviderLoaderInterface::class);

        $container->singleton(ManualDiscovery::class, static fn (): ManualDiscovery => new ManualDiscovery());
        $container->alias(ManualDiscovery::class, DiscoveryInterface::class);
    }

    private function registerLifecycle(ContainerInterface $container): void
    {
        $container->singleton(LifecycleManager::class, static function (ContainerInterface $c): LifecycleManager {
            return new LifecycleManager(
                $c->get(ExtensionRegistryInterface::class),
                $c->get(ServiceProviderLoaderInterface::class),
                $c,
                $c->get(EventDispatcherInterface::class),
                $c->get(FeatureFlags::class),
                $c->get(VersionManager::class),
            );
        });
        $container->alias(LifecycleManager::class, LifecycleManagerInterface::class);

        $container->singleton(ExtensionBootstrapper::class, static function (ContainerInterface $c) {
            return new ExtensionBootstrapper(
                $c->get(DiscoveryInterface::class),
                $c->get(CompatibilityCheckerInterface::class),
                $c->get(DependencyResolverInterface::class),
                $c->get(LifecycleManagerInterface::class),
            );
        });
    }

    private function registerManager(ContainerInterface $container): void
    {
        $container->singleton(ExtensionManager::class, static function (ContainerInterface $c): ExtensionManager {
            return new ExtensionManager(
                $c->get(DiscoveryInterface::class),
                $c->get(ExtensionRegistryInterface::class),
                $c->get(LifecycleManagerInterface::class),
                $c->get(ResourceLoaderInterface::class),
                $c->get(FeatureFlagsInterface::class),
                $c->get(ExtensionBootstrapper::class),
            );
        });
        $container->alias(ExtensionManager::class, ExtensionManagerInterface::class);
        $container->alias(ExtensionManager::class, 'extensions');
        $container->alias(ExtensionManager::class, 'sdk');
    }
}
