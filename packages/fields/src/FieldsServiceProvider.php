<?php

declare(strict_types=1);

namespace OpenMeta\Fields;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Schema\Schema;
use OpenMeta\Fields\Conditions\ConditionEvaluator;
use OpenMeta\Fields\Contracts\FieldEngineInterface;
use OpenMeta\Fields\Contracts\FieldFactoryInterface;
use OpenMeta\Fields\Contracts\FieldHydratorInterface;
use OpenMeta\Fields\Contracts\FieldManagerInterface;
use OpenMeta\Fields\Contracts\FieldRendererInterface;
use OpenMeta\Fields\Contracts\FieldSerializerInterface;
use OpenMeta\Fields\Contracts\FieldStorageInterface;
use OpenMeta\Fields\Factory\FieldFactory;
use OpenMeta\Fields\GraphQL\FieldGraphQLTypeMap;
use OpenMeta\Fields\Groups\FieldGroupRegistry;
use OpenMeta\Fields\Hydration\FieldHydrator;
use OpenMeta\Fields\Lifecycle\FieldLifecycle;
use OpenMeta\Fields\Manager\FieldManager;
use OpenMeta\Fields\Registry\FieldRegistry;
use OpenMeta\Fields\Rendering\FieldRenderer;
use OpenMeta\Fields\Rendering\RendererRegistry;
use OpenMeta\Fields\Rest\FieldRestSerializer;
use OpenMeta\Fields\Serialization\ArraySerializer;
use OpenMeta\Fields\Serialization\JsonSerializer;
use OpenMeta\Fields\Serialization\ObjectSerializer;
use OpenMeta\Fields\Serialization\SerializerRegistry;
use OpenMeta\Fields\Storage\FieldValueStorage;
use OpenMeta\Fields\Storage\StorageRegistry;
use OpenMeta\Fields\Support\BuiltInTypes;
use OpenMeta\Fields\Validation\FieldValidator;

/**
 * Binds registry, factory, manager, storage, rendering, serializers. Registers built-ins on boot.
 */
final class FieldsServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(FieldRegistry::class, static function (ContainerInterface $c): FieldRegistry {
            return new FieldRegistry($c->get(EventDispatcherInterface::class));
        });

        $container->singleton(FieldGroupRegistry::class, static fn (): FieldGroupRegistry => new FieldGroupRegistry());
        $container->singleton(ConditionEvaluator::class, static fn (): ConditionEvaluator => new ConditionEvaluator());
        $container->singleton(FieldValidator::class, static fn (): FieldValidator => new FieldValidator());
        $container->singleton(FieldRenderer::class, static fn (): FieldRenderer => new FieldRenderer());
        $container->alias(FieldRenderer::class, FieldRendererInterface::class);

        $container->singleton(RendererRegistry::class, static function (ContainerInterface $c): RendererRegistry {
            $registry = new RendererRegistry();
            $registry->register('default', $c->get(FieldRendererInterface::class));

            return $registry;
        });

        $container->singleton(ArraySerializer::class, static fn (): ArraySerializer => new ArraySerializer());
        $container->singleton(JsonSerializer::class, static fn (): JsonSerializer => new JsonSerializer());
        $container->singleton(ObjectSerializer::class, static fn (): ObjectSerializer => new ObjectSerializer());

        $container->singleton(SerializerRegistry::class, static function (ContainerInterface $c): SerializerRegistry {
            $registry = new SerializerRegistry();
            $registry->register('array', $c->get(ArraySerializer::class));
            $registry->register('json', $c->get(JsonSerializer::class));
            $registry->register('object', $c->get(ObjectSerializer::class));

            return $registry;
        });

        $container->singleton(
            FieldSerializerInterface::class,
            static fn (ContainerInterface $c): FieldSerializerInterface => $c->get(ArraySerializer::class)
        );

        $container->singleton(FieldHydrator::class, static fn (): FieldHydrator => new FieldHydrator());
        $container->alias(FieldHydrator::class, FieldHydratorInterface::class);

        $container->singleton(
            FieldRestSerializer::class,
            static fn (): FieldRestSerializer => new FieldRestSerializer()
        );
        $container->singleton(
            FieldGraphQLTypeMap::class,
            static fn (): FieldGraphQLTypeMap => new FieldGraphQLTypeMap()
        );

        $container->singleton(FieldValueStorage::class, static function (ContainerInterface $c): FieldValueStorage {
            return new FieldValueStorage(
                $c->get(ConnectionInterface::class),
                $c->get(Schema::class),
            );
        });
        $container->alias(FieldValueStorage::class, FieldStorageInterface::class);

        $container->singleton(StorageRegistry::class, static function (ContainerInterface $c): StorageRegistry {
            $registry = new StorageRegistry();
            $registry->register('database', $c->get(FieldStorageInterface::class));

            return $registry;
        });

        $container->singleton(FieldFactory::class, static function (ContainerInterface $c): FieldFactory {
            return new FieldFactory(
                $c->get(FieldRegistry::class),
                $c->get(EventDispatcherInterface::class),
            );
        });
        $container->alias(FieldFactory::class, FieldFactoryInterface::class);

        $container->singleton(FieldLifecycle::class, static function (ContainerInterface $c): FieldLifecycle {
            return new FieldLifecycle(
                $c->get(FieldFactory::class),
                $c->get(FieldValidator::class),
                $c->get(FieldStorageInterface::class),
                $c->get(FieldHydratorInterface::class),
                $c->get(FieldSerializerInterface::class),
                $c->get(EventDispatcherInterface::class),
            );
        });

        $container->singleton(FieldManager::class, static function (ContainerInterface $c): FieldManager {
            return new FieldManager($c->get(FieldLifecycle::class));
        });
        $container->alias(FieldManager::class, FieldManagerInterface::class);

        $container->singleton(FieldEngine::class, static function (ContainerInterface $c): FieldEngine {
            return new FieldEngine(
                $c->get(FieldRegistry::class),
                $c->get(FieldFactoryInterface::class),
                $c->get(FieldManagerInterface::class),
                $c->get(FieldRendererInterface::class),
            );
        });

        $container->alias(FieldEngine::class, FieldEngineInterface::class);
        $container->alias(FieldEngine::class, 'fields');
        $container->alias(FieldRegistry::class, 'fields.registry');
        $container->alias(FieldFactory::class, 'fields.factory');
        $container->alias(FieldManager::class, 'fields.manager');
    }

    public function boot(ContainerInterface $container): void
    {
        /** @var FieldRegistry $registry */
        $registry = $container->get(FieldRegistry::class);
        $registry->discover(BuiltInTypes::map());
        $registry->alias('multi_select', 'multiselect');
        $registry->alias('bool', 'boolean');

        /** @var FieldValueStorage $storage */
        $storage = $container->get(FieldValueStorage::class);
        $storage->ensureTable();
    }
}
