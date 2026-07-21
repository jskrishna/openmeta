<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Output;

use OpenMeta\Cli\Contracts\OutputInterface;

/**
 * A minimal progress indicator: `[####------]  40%`.
 */
final class ProgressBar
{
    private int $current = 0;

    public function __construct(
        private readonly OutputInterface $output,
        private readonly int $total,
        private readonly int $width = 20,
    ) {
    }

    public function advance(int $step = 1): void
    {
        $this->current = min($this->total, $this->current + $step);
        $this->render();
    }

    public function finish(): void
    {
        $this->current = $this->total;
        $this->render();
        $this->output->writeln('');
    }

    public function render(): void
    {
        $ratio = $this->total > 0 ? $this->current / $this->total : 1.0;
        $filled = (int) round($ratio * $this->width);
        $bar = str_repeat('#', $filled) . str_repeat('-', $this->width - $filled);

        $this->output->write(sprintf("\r[%s] %3d%%", $bar, (int) round($ratio * 100)));
    }
}
