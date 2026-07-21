<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\Security;

use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Tests\DatabaseTestCase;

final class SqlInjectionGuardTest extends DatabaseTestCase
{
    public function test_user_input_is_not_executed_as_sql(): void
    {
        $this->schema->create('posts', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
        });

        $evil = "1; DROP TABLE posts;--";
        (new QueryBuilder($this->connection))->from('posts')->insert(['title' => 'safe']);
        $rows = (new QueryBuilder($this->connection))->from('posts')->where('title', '=', $evil)->get();

        $this->assertSame([], $rows);
        $this->assertSame(1, (new QueryBuilder($this->connection))->from('posts')->count());
    }
}
