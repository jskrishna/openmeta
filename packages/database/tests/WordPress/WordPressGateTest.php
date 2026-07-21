<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\WordPress;

use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Tests\DatabaseTestCase;

/**
 * Database uses MemoryConnection in CI; WP $wpdb bridge is optional / future.
 */
final class WordPressGateTest extends DatabaseTestCase
{
    public function test_memory_driver_works_without_wpdb(): void
    {
        $this->assertArrayNotHasKey('wpdb', $GLOBALS);

        $this->schema->create('wp_smoke', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        (new QueryBuilder($this->connection))->from('wp_smoke')->insert(['name' => 'ok']);
        $this->assertSame(1, (new QueryBuilder($this->connection))->from('wp_smoke')->count());
    }
}
