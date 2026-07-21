<?php

declare(strict_types=1);

namespace OpenMeta\Generator;

use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Cli\Discovery\CommandDiscovery;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Contracts\ConflictDetectorInterface;
use OpenMeta\Generator\Contracts\GeneratorManagerInterface;
use OpenMeta\Generator\Contracts\GeneratorRegistryInterface;
use OpenMeta\Generator\Contracts\NamespaceResolverInterface;
use OpenMeta\Generator\Contracts\PlaceholderResolverInterface;
use OpenMeta\Generator\Contracts\StubLoaderInterface;
use OpenMeta\Generator\Contracts\TemplateEngineInterface;
use OpenMeta\Generator\Files\ConflictDetector;
use OpenMeta\Generator\Files\FileGenerator;
use OpenMeta\Generator\Manager\GeneratorManager;
use OpenMeta\Generator\Registry\GeneratorFactory;
use OpenMeta\Generator\Registry\GeneratorRegistry;
use OpenMeta\Generator\Resolvers\NamespaceResolver;
use OpenMeta\Generator\Resolvers\PlaceholderResolver;
use OpenMeta\Generator\Stubs\StubLoader;
use OpenMeta\Generator\Support\GeneratorCommandProvider;
use OpenMeta\Generator\Templates\TemplateEngine;
use OpenMeta\Support\Filesystem\LocalFilesystem;

/**
 * Registers the code generator and mounts its `make:*` commands into the CLI.
 */
final class GeneratorServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $stubPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'stubs';

        $container->singleton(TemplateEngine::class, static fn (): TemplateEngine => new TemplateEngine());
        $container->alias(TemplateEngine::class, TemplateEngineInterface::class);

        $container->singleton(
            StubLoader::class,
            static fn (): StubLoader => new StubLoader(new LocalFilesystem(), [$stubPath]),
        );
        $container->alias(StubLoader::class, StubLoaderInterface::class);

        $container->singleton(
            PlaceholderResolver::class,
            static fn (): PlaceholderResolver => new PlaceholderResolver(),
        );
        $container->alias(PlaceholderResolver::class, PlaceholderResolverInterface::class);

        $container->singleton(NamespaceResolver::class, static fn (): NamespaceResolver => new NamespaceResolver());
        $container->alias(NamespaceResolver::class, NamespaceResolverInterface::class);

        $container->singleton(
            GeneratorConfiguration::class,
            static fn (): GeneratorConfiguration => new GeneratorConfiguration(),
        );

        $container->singleton(GeneratorFactory::class, static function (ContainerInterface $c): GeneratorFactory {
            return new GeneratorFactory(
                $c->get(StubLoaderInterface::class),
                $c->get(TemplateEngineInterface::class),
                $c->get(PlaceholderResolverInterface::class),
                $c->get(NamespaceResolverInterface::class),
            );
        });

        $container->singleton(GeneratorRegistry::class, static function (ContainerInterface $c): GeneratorRegistry {
            $registry = new GeneratorRegistry();
            /** @var GeneratorFactory $factory */
            $factory = $c->get(GeneratorFactory::class);

            foreach ($factory->defaults() as $generator) {
                $registry->register($generator);
            }

            return $registry;
        });
        $container->alias(GeneratorRegistry::class, GeneratorRegistryInterface::class);

        $container->singleton(ConflictDetector::class, static function (ContainerInterface $c): ConflictDetector {
            return new ConflictDetector(new LocalFilesystem(), $c->get(NamespaceResolverInterface::class));
        });
        $container->alias(ConflictDetector::class, ConflictDetectorInterface::class);

        $container->singleton(
            FileGenerator::class,
            static fn (): FileGenerator => new FileGenerator(new LocalFilesystem()),
        );

        $container->singleton(GeneratorManager::class, static function (ContainerInterface $c): GeneratorManager {
            return new GeneratorManager(
                $c->get(GeneratorRegistryInterface::class),
                $c->get(FileGenerator::class),
                $c->get(ConflictDetectorInterface::class),
                $c->get(GeneratorConfiguration::class),
                $c->get(EventDispatcherInterface::class),
            );
        });
        $container->alias(GeneratorManager::class, GeneratorManagerInterface::class);
        $container->alias(GeneratorManager::class, 'generator');
    }

    public function boot(ContainerInterface $container): void
    {
        if (! $container->has(CommandRegistryInterface::class)) {
            return;
        }

        $discovery = new CommandDiscovery($container->get(CommandRegistryInterface::class));
        $discovery->fromProviders([
            new GeneratorCommandProvider($container->get(GeneratorManagerInterface::class)),
        ]);
    }
}
