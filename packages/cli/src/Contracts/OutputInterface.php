<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

use OpenMeta\Cli\Output\Verbosity;

/**
 * Console output sink with leveled messages and structured helpers.
 */
interface OutputInterface
{
    public function write(string $message): void;

    public function writeln(string $message = ''): void;

    public function success(string $message): void;

    public function warning(string $message): void;

    public function error(string $message): void;

    public function info(string $message): void;

    public function comment(string $message): void;

    /**
     * @param list<string>       $headers
     * @param list<list<string>> $rows
     */
    public function table(array $headers, array $rows): void;

    public function json(mixed $data): void;

    public function setVerbosity(Verbosity $verbosity): void;

    public function verbosity(): Verbosity;
}
