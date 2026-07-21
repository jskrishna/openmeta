<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Contracts;

/**
 * Undo / redo / snapshot transactions for builder sessions.
 */
interface HistoryManagerInterface
{
    public function record(callable $mutator): mixed;

    public function undo(): bool;

    public function redo(): bool;

    public function canUndo(): bool;

    public function canRedo(): bool;

    public function beginTransaction(): void;

    public function commitTransaction(): void;

    public function rollbackTransaction(): void;
}
