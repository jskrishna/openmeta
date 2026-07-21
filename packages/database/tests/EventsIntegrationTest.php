<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Database\DatabaseServiceProvider;
use OpenMeta\Database\Events\ConnectionOpened;
use OpenMeta\Database\Events\MigrationCompleted;
use OpenMeta\Database\Events\MigrationStarted;
use OpenMeta\Database\Events\TransactionCommitted;
use OpenMeta\Database\Events\TransactionStarted;
use OpenMeta\Database\Migrations\Migrator;
use OpenMeta\Database\Tests\Fixtures\CreatePostsMigration;
use OpenMeta\Database\Transactions\TransactionManager;

final class EventsIntegrationTest extends DatabaseTestCase
{
    public function test_core_event_dispatcher_receives_database_events(): void
    {
        $app = Bootstrap::run(
            ['database' => ['default' => 'memory', 'connections' => [
                'memory' => ['driver' => 'memory', 'prefix' => 'ev_'],
            ]]],
            [DatabaseServiceProvider::class]
        );

        /** @var EventDispatcherInterface $events */
        $events = $app->get(EventDispatcherInterface::class);
        $seen = [];

        $events->listen(ConnectionOpened::class, static function (ConnectionOpened $e) use (&$seen): void {
            $seen[] = 'connection:' . $e->driver;
        });
        $events->listen(TransactionStarted::class, static function () use (&$seen): void {
            $seen[] = 'tx:start';
        });
        $events->listen(TransactionCommitted::class, static function () use (&$seen): void {
            $seen[] = 'tx:commit';
        });
        $events->listen(MigrationStarted::class, static function (MigrationStarted $e) use (&$seen): void {
            $seen[] = 'mig:start:' . $e->migration;
        });
        $events->listen(MigrationCompleted::class, static function (MigrationCompleted $e) use (&$seen): void {
            $seen[] = 'mig:done:' . $e->migration;
        });

        // Re-resolve manager so ConnectionOpened fires for this boot path listeners
        // (ConnectionOpened already fired during provider register — assert tx + migration.)
        /** @var TransactionManager $tx */
        $tx = $app->get(TransactionManager::class);
        $tx->run(static function (): void {
        });

        /** @var Migrator $migrator */
        $migrator = $app->get(Migrator::class);
        $migrator->migrate([CreatePostsMigration::class]);

        self::assertContains('tx:start', $seen);
        self::assertContains('tx:commit', $seen);
        self::assertTrue(
            (bool) array_filter($seen, static fn (string $s): bool => str_starts_with($s, 'mig:start:'))
        );
        self::assertTrue(
            (bool) array_filter($seen, static fn (string $s): bool => str_starts_with($s, 'mig:done:'))
        );
    }
}
