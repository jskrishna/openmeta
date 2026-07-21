<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests;

use OpenMeta\Database\Connections\ConnectionFactory;
use OpenMeta\Database\Connections\ConnectionManager;
use OpenMeta\Database\Connections\ConnectionRegistry;
use OpenMeta\Database\Connections\MemoryConnection;
use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Transactions\TransactionManager;

final class ConnectionAndTransactionTest extends DatabaseTestCase
{
    public function test_connection_manager_and_factory(): void
    {
        $registry = new ConnectionRegistry();
        $factory = new ConnectionFactory();
        $manager = new ConnectionManager($registry, $factory);
        $manager->add('primary', ['driver' => 'memory', 'prefix' => 't_']);
        $registry->setDefault('primary');

        self::assertSame('memory', $manager->connection()->driver());
        self::assertTrue($registry->has('primary'));
    }

    public function test_transaction_manager_run(): void
    {
        $this->schema->create('items', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });

        $tx = new TransactionManager($this->connection);
        $tx->run(function (): void {
            (new QueryBuilder($this->connection))->from('items')->insert(['name' => 'a']);
        });

        self::assertSame(1, (new QueryBuilder($this->connection))->from('items')->count());
        self::assertSame(0, $tx->level());
    }

    public function test_query_where_in_or_null_paginate(): void
    {
        $this->schema->create('posts', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('status', 32, true);
        });

        $q = new QueryBuilder($this->connection);
        $q->from('posts')->insert(['title' => 'A', 'status' => 'pub']);
        $q->from('posts')->insert(['title' => 'B', 'status' => 'draft']);
        $q->from('posts')->insert(['title' => 'C', 'status' => null]);

        self::assertCount(2, $q->from('posts')->whereIn('status', ['pub', 'draft'])->get());
        self::assertCount(1, $q->from('posts')->whereNull('status')->get());
        self::assertTrue(
            $q->from('posts')->where('title', 'A')->orWhere('title', 'B')->count() >= 2
        );

        $page = $q->from('posts')->orderBy('id')->paginate(2, 1);
        self::assertSame(3, $page->total());
        self::assertSame(2, $page->items()->count());
        self::assertSame(2, $page->lastPage());
    }
}
