<?php

declare(strict_types=1);

namespace OpenMeta\Database;

use OpenMeta\Core\Contracts\ConfigRepositoryInterface;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Database\Configuration\DatabaseConfig;
use OpenMeta\Database\Connections\ConnectionFactory;
use OpenMeta\Database\Connections\ConnectionManager;
use OpenMeta\Database\Connections\ConnectionRegistry;
use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Events\ConnectionOpened;
use OpenMeta\Database\Metadata\SchemaInspector;
use OpenMeta\Database\Migrations\Migrator;
use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Relationships\RelationLoader;
use OpenMeta\Database\Schema\Schema;
use OpenMeta\Database\Transactions\TransactionManager;

/**
 * Binds the Database Abstraction Layer. No UI / HTTP / WordPress wpdb.
 */
final class DatabaseServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(ConnectionFactory::class, static fn (): ConnectionFactory => new ConnectionFactory());
        $container->singleton(ConnectionRegistry::class, static fn (): ConnectionRegistry => new ConnectionRegistry());
        $container->singleton(SchemaInspector::class, static fn (): SchemaInspector => new SchemaInspector());

        $container->singleton(ConnectionManager::class, static function (ContainerInterface $c): ConnectionManager {
            /** @var ConfigRepositoryInterface $configRepo */
            $configRepo = $c->get(ConfigRepositoryInterface::class);
            $dbConfig = DatabaseConfig::fromArray([
                'default' => (string) $configRepo->get('database.default', 'memory'),
                'connections' => (array) $configRepo->get('database.connections', [
                    'memory' => ['driver' => 'memory', 'prefix' => 'om_'],
                ]),
            ]);

            $registry = $c->get(ConnectionRegistry::class);
            $factory = $c->get(ConnectionFactory::class);
            $manager = new ConnectionManager($registry, $factory);

            $default = $dbConfig->default;
            if (! isset($dbConfig->connections[$default])) {
                $default = 'memory';
            }

            foreach ($dbConfig->connections as $name => $connectionConfig) {
                $manager->add($name, $connectionConfig);
            }

            if (! $registry->has($default)) {
                $manager->add($default, ['driver' => 'memory', 'prefix' => 'om_']);
            }

            $registry->setDefault($default);

            $connection = $registry->get($default);
            if ($c->has(EventDispatcherInterface::class)) {
                $c->get(EventDispatcherInterface::class)->dispatch(
                    new ConnectionOpened($default, $connection->driver())
                );
            }

            return $manager;
        });

        $container->singleton(ConnectionInterface::class, static function (ContainerInterface $c): ConnectionInterface {
            return $c->get(ConnectionManager::class)->connection();
        });

        $container->singleton(Schema::class, static function (ContainerInterface $c): Schema {
            return new Schema($c->get(ConnectionInterface::class));
        });

        $container->singleton(Migrator::class, static function (ContainerInterface $c): Migrator {
            $events = $c->has(EventDispatcherInterface::class)
                ? $c->get(EventDispatcherInterface::class)
                : null;

            return new Migrator(
                $c->get(ConnectionInterface::class),
                $c->get(Schema::class),
                $events,
            );
        });

        $container->singleton(RelationLoader::class, static function (ContainerInterface $c): RelationLoader {
            return new RelationLoader($c->get(ConnectionInterface::class));
        });

        $container->singleton(TransactionManager::class, static function (ContainerInterface $c): TransactionManager {
            $events = $c->has(EventDispatcherInterface::class)
                ? $c->get(EventDispatcherInterface::class)
                : null;

            return new TransactionManager($c->get(ConnectionInterface::class), $events);
        });

        $container->bind(QueryBuilder::class, static function (ContainerInterface $c): QueryBuilder {
            return new QueryBuilder($c->get(ConnectionInterface::class));
        });

        $container->alias(ConnectionInterface::class, 'db');
        $container->alias(ConnectionManager::class, 'db.manager');
        $container->alias(Schema::class, 'db.schema');
        $container->alias(Migrator::class, 'db.migrator');
        $container->alias(TransactionManager::class, 'db.transactions');
    }

    public function boot(ContainerInterface $container): void
    {
    }
}
