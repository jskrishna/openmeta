<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Support;

use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Output\Verbosity;
use Throwable;

/**
 * Renders a thrown exception to the console, adding a trace at debug verbosity.
 */
final class ConsoleErrorHandler
{
    public function render(Throwable $exception, OutputInterface $output): void
    {
        $output->error($exception->getMessage());

        if ($output->verbosity()->allows(Verbosity::Debug)) {
            $output->comment($exception->getTraceAsString());
        }
    }
}
