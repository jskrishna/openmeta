<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Unit;

use OpenMeta\Builder\History\HistoryManager;
use OpenMeta\Builder\Schema\SchemaVersion;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class HistoryManagerTest extends BuilderTestCase
{
    public function test_undo_redo_schema_mutations(): void
    {
        $this->grant('manage_options');
        $history = $this->builder->history();

        $history->record(function (): void {
            $this->dragDrop->dropNew($this->canvas, 'text', 'first');
        });
        $this->assertSame(1, $this->canvas->count());

        $history->record(function (): void {
            $this->dragDrop->dropNew($this->canvas, 'text', 'second');
        });
        $this->assertSame(2, $this->canvas->count());
        $this->assertTrue($history->canUndo());

        $this->assertTrue($history->undo());
        $this->assertSame(1, $this->canvas->count());
        $this->assertTrue($history->canRedo());

        $this->assertTrue($history->redo());
        $this->assertSame(2, $this->canvas->count());
    }

    public function test_transaction_groups_steps(): void
    {
        $history = new HistoryManager(
            fn (): array => ['count' => $this->canvas->count()],
            function (array $state): void {
                $target = (int) ($state['count'] ?? 0);
                while ($this->canvas->count() > $target) {
                    $node = $this->canvas->nodes()[0];
                    $this->canvas->remove($node->id);
                }
            },
        );

        $history->beginTransaction();
        $history->record(function (): void {
            $this->dragDrop->dropNew($this->canvas, 'text', 'a');
        });
        $history->record(function (): void {
            $this->dragDrop->dropNew($this->canvas, 'text', 'b');
        });
        $history->commitTransaction();

        $this->assertSame(2, $this->canvas->count());
        $this->assertTrue($history->undo());
        $this->assertSame(0, $this->canvas->count());
    }
}
