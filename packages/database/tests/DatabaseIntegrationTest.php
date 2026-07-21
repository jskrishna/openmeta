<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\DatabaseServiceProvider;
use OpenMeta\Database\Migrations\Migrator;
use OpenMeta\Database\Repositories\TableRepository;
use OpenMeta\Database\Schema\Schema;
use OpenMeta\Database\Tests\Fixtures\CreatePostsMigration;

final class DatabaseIntegrationTest extends \PHPUnit\Framework\TestCase
{
    public function test_provider_connection_migration_and_repository_crud(): void
    {
        $app = Bootstrap::run(
            [
                'database' => [
                    'default' => 'memory',
                    'connections' => [
                        'memory' => [
                            'driver' => 'memory',
                            'prefix' => 'om_',
                        ],
                    ],
                ],
            ],
            [DatabaseServiceProvider::class]
        );

        /** @var ConnectionInterface $connection */
        $connection = $app->get(ConnectionInterface::class);
        /** @var Schema $schema */
        $schema = $app->get(Schema::class);
        /** @var Migrator $migrator */
        $migrator = $app->get(Migrator::class);

        $migrator->migrate([CreatePostsMigration::class]);
        self::assertTrue($schema->hasTable('posts'));

        $repo = new TableRepository($connection, 'posts');
        $row = $repo->create(['title' => 'Hello', 'body' => 'World']);
        self::assertSame('Hello', $repo->find($row['id'])['title'] ?? null);
        self::assertTrue($app->has('db'));
        self::assertTrue($app->has('db.migrator'));
    }
}
