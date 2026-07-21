<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests;

use OpenMeta\Database\Repositories\TableRepository;
use OpenMeta\Database\Schema\Blueprint;

final class RepositoryTest extends DatabaseTestCase
{
    private TableRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->schema->create('items', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->integer('qty');
        });
        $this->repository = new TableRepository($this->connection, 'items');
    }

    public function test_crud_works(): void
    {
        $created = $this->repository->create(['name' => 'Widget', 'qty' => 2]);
        self::assertArrayHasKey('id', $created);
        self::assertSame('Widget', $created['name']);

        $found = $this->repository->find($created['id']);
        self::assertNotNull($found);
        self::assertSame('Widget', $found['name']);

        $updated = $this->repository->update($created['id'], ['qty' => 9]);
        self::assertNotNull($updated);
        self::assertSame(9, (int) $updated['qty']);

        self::assertCount(1, $this->repository->all());
        self::assertTrue($this->repository->delete($created['id']));
        self::assertNull($this->repository->find($created['id']));
        self::assertSame([], $this->repository->all());
    }

    public function test_repository_query_abstraction(): void
    {
        $this->repository->create(['name' => 'A', 'qty' => 1]);
        $this->repository->create(['name' => 'B', 'qty' => 5]);

        $row = $this->repository->query()->where('qty', '>', 3)->first();
        self::assertNotNull($row);
        self::assertSame('B', $row['name']);
    }
}
