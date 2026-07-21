<?php

declare(strict_types=1);

namespace OpenMeta\Database\Contracts;

/**
 * Migration contract.
 */
interface MigrationInterface
{
    public function name(): string;

    public function up(\OpenMeta\Database\Schema\Schema $schema): void;

    public function down(\OpenMeta\Database\Schema\Schema $schema): void;
}
