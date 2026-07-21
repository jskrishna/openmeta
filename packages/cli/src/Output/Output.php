<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Output;

use OpenMeta\Cli\Contracts\OutputFormatterInterface;
use OpenMeta\Cli\Contracts\OutputInterface;

/**
 * Shared output behaviour: leveled messages, tables, and JSON on top of a
 * single {@see doWrite()} sink implemented by concrete outputs.
 */
abstract class Output implements OutputInterface
{
    protected Verbosity $verbosity = Verbosity::Normal;

    protected TableRenderer $tables;

    public function __construct(protected readonly OutputFormatterInterface $formatter)
    {
        $this->tables = new TableRenderer();
    }

    abstract protected function doWrite(string $bytes): void;

    public function write(string $message): void
    {
        if ($this->verbosity === Verbosity::Quiet) {
            return;
        }

        $this->doWrite($message);
    }

    public function writeln(string $message = ''): void
    {
        $this->write($message . "\n");
    }

    public function success(string $message): void
    {
        $this->writeln($this->formatter->format('success', $message));
    }

    public function warning(string $message): void
    {
        $this->writeln($this->formatter->format('warning', $message));
    }

    public function error(string $message): void
    {
        $this->writeln($this->formatter->format('error', $message));
    }

    public function info(string $message): void
    {
        $this->writeln($this->formatter->format('info', $message));
    }

    public function comment(string $message): void
    {
        $this->writeln($this->formatter->format('comment', $message));
    }

    public function table(array $headers, array $rows): void
    {
        $this->writeln($this->tables->render($headers, $rows));
    }

    public function json(mixed $data): void
    {
        $this->writeln((string) json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    }

    public function setVerbosity(Verbosity $verbosity): void
    {
        $this->verbosity = $verbosity;
    }

    public function verbosity(): Verbosity
    {
        return $this->verbosity;
    }

    public function formatter(): OutputFormatterInterface
    {
        return $this->formatter;
    }
}
