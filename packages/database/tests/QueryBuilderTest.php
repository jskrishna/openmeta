<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests;

use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;

final class QueryBuilderTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->schema->create('posts', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->integer('views');
        });
    }

    public function test_insert_where_order_limit(): void
    {
        $query = new QueryBuilder($this->connection);
        $query->from('posts')->insert(['title' => 'A', 'views' => 1]);
        $query->from('posts')->insert(['title' => 'B', 'views' => 5]);
        $query->from('posts')->insert(['title' => 'C', 'views' => 3]);

        $rows = (new QueryBuilder($this->connection))
            ->from('posts')
            ->where('views', '>=', 3)
            ->orderBy('views', 'desc')
            ->get();

        self::assertCount(2, $rows);
        self::assertSame('B', $rows[0]['title']);
        self::assertSame(1, (new QueryBuilder($this->connection))->from('posts')->where('title', 'A')->count());
    }
}
