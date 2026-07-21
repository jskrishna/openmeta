<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Unit;

use OpenMeta\Cli\Output\BufferedOutput;
use OpenMeta\Cli\Output\OutputFormatter;
use OpenMeta\Cli\Prompts\Prompt;
use OpenMeta\Cli\Tests\CliTestCase;

final class PromptTest extends CliTestCase
{
    private function prompt(string ...$lines): Prompt
    {
        return new Prompt(
            new BufferedOutput(new OutputFormatter(false)),
            $this->inputStream(...$lines),
        );
    }

    public function test_ask_returns_answer(): void
    {
        self::assertSame('Ada', $this->prompt('Ada')->ask('Name'));
    }

    public function test_ask_falls_back_to_default_on_empty(): void
    {
        self::assertSame('Bob', $this->prompt('')->ask('Name', 'Bob'));
    }

    public function test_confirm(): void
    {
        self::assertTrue($this->prompt('y')->confirm('Proceed?'));
        self::assertFalse($this->prompt('n')->confirm('Proceed?', true));
        self::assertTrue($this->prompt('')->confirm('Proceed?', true));
    }

    public function test_choice_by_index_and_value(): void
    {
        self::assertSame('b', $this->prompt('1')->choice('Pick', ['a', 'b', 'c']));
        self::assertSame('c', $this->prompt('c')->choice('Pick', ['a', 'b', 'c']));
    }
}
