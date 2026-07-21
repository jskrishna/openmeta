<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests;

use OpenMeta\Database\Connections\MemoryConnection;
use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Migrations\Migrator;
use OpenMeta\Database\Schema\Schema;

abstract class DatabaseTestCase extends \PHPUnit\Framework\TestCase
{
    protected ConnectionInterface $connection;

    protected Schema $schema;

    protected Migrator $migrator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = new MemoryConnection('om_');
        $this->schema = new Schema($this->connection);
        $this->migrator = new Migrator($this->connection, $this->schema);
    }
}
