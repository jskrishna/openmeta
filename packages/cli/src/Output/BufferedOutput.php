<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Output;

/**
 * Captures output in memory — used by tests and JSON/task capture.
 */
final class BufferedOutput extends Output
{
    private string $buffer = '';

    protected function doWrite(string $bytes): void
    {
        $this->buffer .= $bytes;
    }

    /**
     * Return the buffered content and clear it.
     */
    public function fetch(): string
    {
        $content = $this->buffer;
        $this->buffer = '';

        return $content;
    }

    public function content(): string
    {
        return $this->buffer;
    }
}
