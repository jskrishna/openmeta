<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

/**
 * Console logger with verbosity-gated levels.
 */
interface LoggerInterface
{
    public function debug(string $message): void;

    public function info(string $message): void;

    public function warning(string $message): void;

    public function error(string $message): void;
}
