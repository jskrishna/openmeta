<?php

declare(strict_types=1);

namespace OpenMeta\Database\Transactions;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Events\TransactionCommitted;
use OpenMeta\Database\Events\TransactionRolledBack;
use OpenMeta\Database\Events\TransactionStarted;
use OpenMeta\Database\Exceptions\TransactionException;
use Throwable;

/**
 * Transaction manager with optional Core event dispatch.
 */
final class TransactionManager
{
    private int $level = 0;

    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly ?EventDispatcherInterface $events = null,
    ) {
    }

    /**
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function run(callable $callback): mixed
    {
        $this->begin();

        try {
            $result = $callback();
            $this->commit();

            return $result;
        } catch (Throwable $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function begin(): void
    {
        if ($this->level === 0) {
            $this->connection->beginTransaction();
            $this->events?->dispatch(new TransactionStarted());
        }

        $this->level++;
    }

    public function commit(): void
    {
        if ($this->level === 0) {
            throw new TransactionException('No active transaction to commit.');
        }

        $this->level--;

        if ($this->level === 0) {
            $this->connection->commit();
            $this->events?->dispatch(new TransactionCommitted());
        }
    }

    public function rollBack(): void
    {
        if ($this->level === 0) {
            throw new TransactionException('No active transaction to roll back.');
        }

        $this->level = 0;
        $this->connection->rollBack();
        $this->events?->dispatch(new TransactionRolledBack());
    }

    public function level(): int
    {
        return $this->level;
    }
}
