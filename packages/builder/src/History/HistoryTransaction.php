<?php

declare(strict_types=1);

namespace OpenMeta\Builder\History;

/**
 * Groups multiple history entries into one undo step.
 */
final class HistoryTransaction
{
    /** @var list<HistorySnapshot> */
    private array $snapshots = [];

    public function push(HistorySnapshot $snapshot): void
    {
        $this->snapshots[] = $snapshot;
    }

    /** @return list<HistorySnapshot> */
    public function snapshots(): array
    {
        return $this->snapshots;
    }

    public function isEmpty(): bool
    {
        return $this->snapshots === [];
    }
}
