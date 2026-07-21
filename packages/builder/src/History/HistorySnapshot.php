<?php

declare(strict_types=1);

namespace OpenMeta\Builder\History;

/**
 * Immutable canvas snapshot for undo / redo stacks.
 */
final class HistorySnapshot
{
    /**
     * @param array<string, mixed> $state
     */
    public function __construct(public readonly array $state)
    {
    }
}
