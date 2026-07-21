<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\Performance;

use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Tests\DatabaseTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class QueryPerformanceTest extends DatabaseTestCase
{
    use AssertsPerformanceBudget;

    public function test_repeated_selects_under_budget(): void
    {
        $this->schema->create('items', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        (new QueryBuilder($this->connection))->from('items')->insert(['name' => 'x']);

        $this->assertUnderMs(300.0, function (): void {
            for ($i = 0; $i < 50; $i++) {
                (new QueryBuilder($this->connection))->from('items')->get();
            }
        }, 'database selects');
    }
}
