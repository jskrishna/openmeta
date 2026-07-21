<?php

declare(strict_types=1);

namespace OpenMeta\Builder\History;

use OpenMeta\Builder\Contracts\HistoryManagerInterface;

/**
 * Undo / redo manager with snapshots and transactions.
 */
final class HistoryManager implements HistoryManagerInterface
{
    /** @var list<HistorySnapshot> */
    private array $undoStack = [];

    /** @var list<HistorySnapshot> */
    private array $redoStack = [];

    private ?HistoryTransaction $transaction = null;

    /** @var callable(): array<string, mixed> */
    private $stateReader;

    /** @var callable(array<string, mixed>): void */
    private $stateWriter;

    /**
     * @param callable(): array<string, mixed> $stateReader
     * @param callable(array<string, mixed>): void $stateWriter
     */
    public function __construct(callable $stateReader, callable $stateWriter)
    {
        $this->stateReader = $stateReader;
        $this->stateWriter = $stateWriter;
    }

    public function record(callable $mutator): mixed
    {
        $before = ($this->stateReader)();
        $result = $mutator();
        $after = ($this->stateReader)();

        if (json_encode($before) !== json_encode($after)) {
            $snapshot = new HistorySnapshot($before);
            if ($this->transaction !== null) {
                $this->transaction->push($snapshot);
            } else {
                $this->undoStack[] = $snapshot;
                $this->redoStack = [];
            }
        }

        return $result;
    }

    public function undo(): bool
    {
        if ($this->undoStack === []) {
            return false;
        }

        $current = ($this->stateReader)();
        $this->redoStack[] = new HistorySnapshot($current);

        $snapshot = array_pop($this->undoStack);
        ($this->stateWriter)($snapshot->state);

        return true;
    }

    public function redo(): bool
    {
        if ($this->redoStack === []) {
            return false;
        }

        $current = ($this->stateReader)();
        $this->undoStack[] = new HistorySnapshot($current);

        $snapshot = array_pop($this->redoStack);
        ($this->stateWriter)($snapshot->state);

        return true;
    }

    public function canUndo(): bool
    {
        return $this->undoStack !== [];
    }

    public function canRedo(): bool
    {
        return $this->redoStack !== [];
    }

    public function beginTransaction(): void
    {
        $this->transaction = new HistoryTransaction();
    }

    public function commitTransaction(): void
    {
        if ($this->transaction === null || $this->transaction->isEmpty()) {
            $this->transaction = null;

            return;
        }

        $first = $this->transaction->snapshots()[0];
        $this->undoStack[] = $first;
        $this->redoStack = [];
        $this->transaction = null;
    }

    public function rollbackTransaction(): void
    {
        if ($this->transaction === null || $this->transaction->isEmpty()) {
            $this->transaction = null;

            return;
        }

        $snapshot = $this->transaction->snapshots()[0];
        ($this->stateWriter)($snapshot->state);
        $this->transaction = null;
    }
}
