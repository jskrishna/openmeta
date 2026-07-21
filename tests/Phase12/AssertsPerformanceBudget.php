<?php

declare(strict_types=1);

namespace OpenMeta\Tests\Phase12;

/**
 * Shared helpers for Phase 12 layer budgets.
 */
trait AssertsPerformanceBudget
{
    protected function assertUnderMs(float $budgetMs, callable $work, string $label = 'budget'): void
    {
        $start = hrtime(true);
        $work();
        $elapsedMs = (hrtime(true) - $start) / 1e6;

        $this->assertLessThan(
            $budgetMs,
            $elapsedMs,
            sprintf('%s exceeded: %.2fms >= %.2fms', $label, $elapsedMs, $budgetMs)
        );
    }
}
