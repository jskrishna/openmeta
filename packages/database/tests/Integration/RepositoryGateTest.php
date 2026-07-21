<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\Integration;

use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Tests\DatabaseTestCase;

final class RepositoryGateTest extends DatabaseTestCase
{
    public function test_schema_and_query_roundtrip(): void
    {
        $this->schema->create('notes', static function (Blueprint $table): void {
            $table->id();
            $table->string('body');
        });

        (new QueryBuilder($this->connection))->from('notes')->insert(['body' => 'hi']);
        $this->assertSame(1, (new QueryBuilder($this->connection))->from('notes')->count());
    }
}
