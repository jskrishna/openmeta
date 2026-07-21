<?php

declare(strict_types=1);

namespace OpenMeta\Extensions;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Extensions\Bootstrap\ExtensionBootstrapper;
use OpenMeta\Extensions\Compatibility\CompatibilityChecker;
use OpenMeta\Extensions\Contracts\CompatibilityCheckerInterface;
use OpenMeta\Extensions\Contracts\DependencyResolverInterface;
use OpenMeta\Extensions\Contracts\DiscoveryInterface;
use OpenMeta\Extensions\Contracts\ExtensionManagerInterface;
use OpenMeta\Extensions\Contracts\ExtensionRegistryInterface;
use OpenMeta\Extensions\Contracts\FeatureFlagsInterface;
use OpenMeta\Extensions\Contracts\LifecycleManagerInterface;
use OpenMeta\Extensions\Contracts\ResourceLoaderInterface;
use OpenMeta\Extensions\Contracts\ServiceProviderLoaderInterface;
use OpenMeta\Extensions\Contracts\VersionComparatorInterface;
use OpenMeta\Extensions\Discovery\ManualDiscovery;
use OpenMeta\Extensions\Lifecycle\LifecycleManager;
use OpenMeta\Extensions\Manager\ExtensionManager;
use OpenMeta\Extensions\Manifest\ManifestFactory;
use OpenMeta\Extensions\Providers\ServiceProviderLoader;
use OpenMeta\Extensions\Registry\ExtensionRegistry;
use OpenMeta\Extensions\Resources\ResourceLoader;
use OpenMeta\Extensions\Support\DependencyResolver;
use OpenMeta\Extensions\Support\FeatureFlags;
use OpenMeta\Extensions\Versioning\VersionComparator;
use OpenMeta\Extensions\Versioning\VersionManager;

/**
 * Registers the Extension SDK services into the container.
 *
 * Public surface (Manager, Registry, Manifest factory, Discovery, Lifecycle)
 * is bound to its interface; supporting collaborators are bound to concretes.
 */
final class ExtensionsServiceProvider extends ServiceProvider
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
