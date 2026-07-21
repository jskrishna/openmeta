<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests;

use OpenMeta\Database\Tests\Fixtures\CreateCommentsMigration;
use OpenMeta\Database\Tests\Fixtures\CreatePostsMigration;

final class MigrationTest extends DatabaseTestCase
{
    public function test_migrate_status_and_rollback(): void
    {
        $migrations = [CreatePostsMigration::class, CreateCommentsMigration::class];

        $this->migrator->migrate($migrations);

        self::assertTrue($this->schema->hasTable('posts'));
        self::assertTrue($this->schema->hasTable('comments'));

        $status = $this->migrator->status($migrations);
        self::assertSame('ran', $status[0]['status']);
        self::assertSame('ran', $status[1]['status']);

        // Second run is restart-safe (no-op).
        $this->migrator->migrate($migrations);

        $this->migrator->rollback($migrations, 1);
        self::assertFalse($this->schema->hasTable('posts'));
        self::assertFalse($this->schema->hasTable('comments'));

        $status = $this->migrator->status($migrations);
        self::assertSame('pending', $status[0]['status']);
    }
}
