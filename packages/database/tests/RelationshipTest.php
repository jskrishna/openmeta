<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests;

use OpenMeta\Database\Relationships\RelationLoader;
use OpenMeta\Database\Repositories\TableRepository;
use OpenMeta\Database\Schema\Blueprint;

final class RelationshipTest extends DatabaseTestCase
{
    public function test_batch_has_many_and_belongs_to(): void
    {
        $this->schema->create('posts', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
        });
        $this->schema->create('comments', static function (Blueprint $table): void {
            $table->id();
            $table->integer('post_id');
            $table->string('body');
        });

        $posts = new TableRepository($this->connection, 'posts');
        $comments = new TableRepository($this->connection, 'comments');

        $p1 = $posts->create(['title' => 'One']);
        $p2 = $posts->create(['title' => 'Two']);
        $comments->create(['post_id' => $p1['id'], 'body' => 'c1']);
        $comments->create(['post_id' => $p1['id'], 'body' => 'c2']);
        $comments->create(['post_id' => $p2['id'], 'body' => 'c3']);

        $loader = new RelationLoader($this->connection);
        $grouped = $loader->hasMany([$p1, $p2], 'comments', 'post_id');

        self::assertCount(2, $grouped[$p1['id']]);
        self::assertCount(1, $grouped[$p2['id']]);

        $parents = $loader->belongsTo(
            $comments->all(),
            'posts',
            'post_id'
        );
        self::assertSame('One', $parents[$p1['id']]['title']);
        self::assertSame('Two', $parents[$p2['id']]['title']);
    }
}
