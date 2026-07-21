<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Logging;

use OpenMeta\Cli\Contracts\LoggerInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Output\Verbosity;

/**
 * Logs through the console output, gating debug/info by verbosity.
 */
final class ConsoleLogger implements LoggerInterface
{
    public function __construct(private readonly OutputInterface $output)
    {
    }

    public function debug(string $message): void
    {
        if ($this->output->verbosity()->allows(Verbosity::Debug)) {
            $this->output->comment('[debug] ' . $message);
        }
    }

    public function info(string $message): void
    {
        if ($this->output->verbosity()->allows(Verbosity::Verbose)) {
            $this->output->info('[info] ' . $message);
        }
    }

    public function warning(string $message): void
    {
        $this->output->warning('[warning] ' . $message);
    }

    public function error(string $message): void
    {
        $this->output->error('[error] ' . $message);
    }
}
