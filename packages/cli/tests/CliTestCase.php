<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests;

use OpenMeta\Cli\Application\ConsoleApplication;
use OpenMeta\Cli\Output\BufferedOutput;
use OpenMeta\Cli\Output\OutputFormatter;
use OpenMeta\Cli\Registry\CommandRegistry;
use OpenMeta\Core\Events\EventDispatcher;
use PHPUnit\Framework\TestCase;

/**
 * Base test case: an in-memory console (buffered output, no ANSI) so tests
 * never write to STDOUT.
 */
abstract class CliTestCase extends TestCase
{
    protected BufferedOutput $output;

    protected EventDispatcher $events;

    protected CommandRegistry $registry;

    protected ConsoleApplication $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->output = new BufferedOutput(new OutputFormatter(false));
        $this->events = new EventDispatcher();
        $this->registry = new CommandRegistry();
        $this->app = new ConsoleApplication($this->registry, $this->output, $this->events);
    }

    /**
     * Open an in-memory stream primed with the given lines (for prompt input).
     *
     * @return resource
     */
    protected function inputStream(string ...$lines)
    {
        $stream = fopen('php://memory', 'r+');

        if (! is_resource($stream)) {
            self::fail('Unable to open memory stream.');
        }

        fwrite($stream, implode("\n", $lines) . "\n");
        rewind($stream);

        return $stream;
    }
}
