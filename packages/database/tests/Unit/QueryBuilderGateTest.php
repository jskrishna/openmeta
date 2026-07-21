<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\Unit;

use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Tests\DatabaseTestCase;

final class QueryBuilderGateTest extends DatabaseTestCase
{
    public function test_where_filters_rows(): void
    {
        $this->schema->create('posts', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
        });

        (new QueryBuilder($this->connection))->from('posts')->insert(['title' => 'A']);
        $rows = (new QueryBuilder($this->connection))->from('posts')->where('title', '=', 'A')->get();
        $this->assertCount(1, $rows);
    }
}
