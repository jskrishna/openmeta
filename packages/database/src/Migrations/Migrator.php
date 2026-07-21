<?php

declare(strict_types=1);

namespace OpenMeta\Database\Migrations;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Events\MigrationCompleted;
use OpenMeta\Database\Events\MigrationStarted;
use OpenMeta\Database\Exceptions\DatabaseException;
use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Schema\Schema;

/**
 * Applies / rolls back migrations. Restart-safe via migrations ledger.
 */
final class Migrator
{
    private const LEDGER = 'migrations';

    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly Schema $schema,
        private readonly ?EventDispatcherInterface $events = null,
    ) {
    }

    /**
     * @param list<Migration|class-string<Migration>> $migrations
     */
    public function migrate(array $migrations): void
    {
        $this->ensureLedger();
        $ran = $this->ran();
        $batch = null;

        foreach ($migrations as $migration) {
            $instance = $this->resolve($migration);
            $name = $instance->name();

            if (in_array($name, $ran, true)) {
                continue;
            }

            $batch ??= $this->nextBatch();
            $this->events?->dispatch(new MigrationStarted($name));
            $instance->up($this->schema);
            $this->query()->insert([
                'migration' => $name,
                'batch' => $batch,
            ]);
            $this->events?->dispatch(new MigrationCompleted($name));
        }
    }

    /**
     * @param list<Migration|class-string<Migration>> $migrations
     */
    public function rollback(array $migrations, int $steps = 1): void
    {
        $this->ensureLedger();

        $batches = [];
        foreach ($this->query()->orderBy('batch', 'desc')->get() as $row) {
            $batch = (int) $row['batch'];
            if (! in_array($batch, $batches, true)) {
                $batches[] = $batch;
            }
            if (count($batches) >= $steps) {
                break;
            }
        }

        if ($batches === []) {
            return;
        }

        $byName = [];
        foreach ($migrations as $migration) {
            $instance = $this->resolve($migration);
            $byName[$instance->name()] = $instance;
        }

        $rows = array_reverse($this->query()->get());
        foreach ($rows as $row) {
            $batch = (int) $row['batch'];
            if (! in_array($batch, $batches, true)) {
                continue;
            }

            $name = (string) $row['migration'];
            if (! isset($byName[$name])) {
                throw new DatabaseException(sprintf('Cannot rollback unknown migration [%s].', $name));
            }

            $byName[$name]->down($this->schema);
            $this->query()->where('migration', $name)->delete();
        }
    }

    /**
     * @param list<Migration|class-string<Migration>> $migrations
     * @return list<array{migration: string, status: string}>
     */
    public function status(array $migrations): array
    {
        $this->ensureLedger();
        $ran = $this->ran();
        $status = [];

        foreach ($migrations as $migration) {
            $instance = $this->resolve($migration);
            $name = $instance->name();
            $status[] = [
                'migration' => $name,
                'status' => in_array($name, $ran, true) ? 'ran' : 'pending',
            ];
        }

        return $status;
    }

    /**
     * @return list<string>
     */
    public function ran(): array
    {
        $this->ensureLedger();

        return array_map(
            static fn (array $row): string => (string) $row['migration'],
            $this->query()->orderBy('id')->get()
        );
    }

    private function ensureLedger(): void
    {
        if ($this->schema->hasTable(self::LEDGER)) {
            return;
        }

        $this->schema->create(self::LEDGER, static function (Blueprint $table): void {
            $table->id();
            $table->string('migration', 255);
            $table->integer('batch');
        });
    }

    private function query(): QueryBuilder
    {
        return (new QueryBuilder($this->connection))->from(self::LEDGER);
    }

    private function nextBatch(): int
    {
        $max = 0;
        foreach ($this->query()->get() as $row) {
            $max = max($max, (int) $row['batch']);
        }

        return $max + 1;
    }

    /**
     * @param Migration|class-string<Migration> $migration
     */
    private function resolve(Migration|string $migration): Migration
    {
        if ($migration instanceof Migration) {
            return $migration;
        }

        if (! is_a($migration, Migration::class, true)) {
            throw new DatabaseException(sprintf('[%s] is not a Migration.', $migration));
        }

        return new $migration();
    }
}
