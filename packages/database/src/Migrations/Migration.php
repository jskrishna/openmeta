<?php

declare(strict_types=1);

namespace OpenMeta\Database\Migrations;

use OpenMeta\Database\Contracts\MigrationInterface;
use OpenMeta\Database\Schema\Schema;

/**
 * Incremental schema change. Implement up/down; Migrator tracks status.
 */
abstract class Migration implements MigrationInterface
{
    abstract public function up(Schema $schema): void;

    abstract public function down(Schema $schema): void;

    public function name(): string
    {
        return static::class;
    }
}
