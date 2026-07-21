<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Output;

use OpenMeta\Cli\Contracts\OutputFormatterInterface;
use RuntimeException;

/**
 * Writes to a stream (STDOUT by default).
 */
final class ConsoleOutput extends Output
{
    /** @var resource */
    private $stream;

    /**
     * @param resource|null $stream
     */
    public function __construct(OutputFormatterInterface $formatter, $stream = null)
    {
        parent::__construct($formatter);

        $resource = $stream ?? fopen('php://stdout', 'w');

        if (! is_resource($resource)) {
            throw new RuntimeException('Unable to open the output stream.');
        }

        $this->stream = $resource;
    }

    protected function doWrite(string $bytes): void
    {
        fwrite($this->stream, $bytes);
    }
}
