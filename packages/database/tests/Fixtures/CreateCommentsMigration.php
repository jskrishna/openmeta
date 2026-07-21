<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\Fixtures;

use OpenMeta\Database\Migrations\Migration;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Schema\Schema;

final class CreateCommentsMigration extends Migration
{
    public function up(Schema $schema): void
    {
        $schema->create('comments', static function (Blueprint $table): void {
            $table->id();
            $table->integer('post_id');
            $table->string('body');
            $table->index('post_id');
        });
    }

    public function down(Schema $schema): void
    {
        $schema->drop('comments');
    }
}
