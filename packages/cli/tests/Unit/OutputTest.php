<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Unit;

use OpenMeta\Cli\Output\BufferedOutput;
use OpenMeta\Cli\Output\OutputFormatter;
use OpenMeta\Cli\Output\Verbosity;
use PHPUnit\Framework\TestCase;

final class OutputTest extends TestCase
{
    private function buffer(bool $decorated = false): BufferedOutput
    {
        return new BufferedOutput(new OutputFormatter($decorated));
    }

    public function test_leveled_messages_are_written(): void
    {
        $output = $this->buffer();
        $output->success('done');
        $output->warning('careful');
        $output->error('broken');

        $content = $output->content();
        self::assertStringContainsString('done', $content);
        self::assertStringContainsString('careful', $content);
        self::assertStringContainsString('broken', $content);
    }

    public function test_formatter_decorates_only_when_enabled(): void
    {
        self::assertSame('x', (new OutputFormatter(false))->format('success', 'x'));
        self::assertStringContainsString("\033[32m", (new OutputFormatter(true))->format('success', 'x'));
    }

    public function test_table_renders_headers_and_rows(): void
    {
        $output = $this->buffer();
        $output->table(['Name', 'Value'], [['php', '8.3']]);

        $content = $output->content();
        self::assertStringContainsString('| Name', $content);
        self::assertStringContainsString('| php', $content);
    }

    public function test_json_output(): void
    {
        $output = $this->buffer();
        $output->json(['a' => 1]);

        self::assertStringContainsString('"a": 1', $output->content());
    }

    public function test_quiet_verbosity_suppresses_output(): void
    {
        $output = $this->buffer();
        $output->setVerbosity(Verbosity::Quiet);
        $output->writeln('hidden');

        self::assertSame('', $output->content());
    }
}
