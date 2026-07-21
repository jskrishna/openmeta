<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\Fixtures;

use OpenMeta\Database\Migrations\Migration;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Schema\Schema;

final class CreatePostsMigration extends Migration
{
    public function up(Schema $schema): void
    {
        $schema->create('posts', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('body', true);
            $table->timestamps();
        });
    }

    public function down(Schema $schema): void
    {
        $schema->drop('posts');
    }
}
