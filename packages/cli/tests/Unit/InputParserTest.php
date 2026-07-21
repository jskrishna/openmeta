<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Unit;

use OpenMeta\Cli\Exceptions\InvalidInputException;
use OpenMeta\Cli\Input\ArgumentDefinition;
use OpenMeta\Cli\Input\InputDefinition;
use OpenMeta\Cli\Input\InputParser;
use OpenMeta\Cli\Input\OptionDefinition;
use PHPUnit\Framework\TestCase;

final class InputParserTest extends TestCase
{
    private InputParser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new InputParser();
    }

    public function test_binds_positional_arguments_and_defaults(): void
    {
        $definition = (new InputDefinition())
            ->addArgument(new ArgumentDefinition('name', true))
            ->addArgument(new ArgumentDefinition('type', false, '', 'string'));

        $input = $this->parser->parse(['posts'], $definition);

        self::assertSame('posts', $input->argument('name'));
        self::assertSame('string', $input->argument('type'));
    }

    public function test_missing_required_argument_throws(): void
    {
        $definition = (new InputDefinition())->addArgument(new ArgumentDefinition('name', true));

        $this->expectException(InvalidInputException::class);
        $this->parser->parse([], $definition);
    }

    public function test_parses_options_in_all_forms(): void
    {
        $definition = (new InputDefinition())
            ->addOption(new OptionDefinition('path', 'p', true))
            ->addOption(new OptionDefinition('force', 'f', false));

        $inline = $this->parser->parse(['--path=src/x.php', '--force'], $definition);
        self::assertSame('src/x.php', $inline->option('path'));
        self::assertTrue($inline->option('force'));

        $spaced = $this->parser->parse(['--path', 'src/y.php'], $definition);
        self::assertSame('src/y.php', $spaced->option('path'));

        $short = $this->parser->parse(['-p', 'src/z.php', '-f'], $definition);
        self::assertSame('src/z.php', $short->option('path'));
        self::assertTrue($short->option('force'));
    }

    public function test_flag_defaults_to_false(): void
    {
        $definition = (new InputDefinition())->addOption(new OptionDefinition('force', 'f', false));

        self::assertFalse($this->parser->parse([], $definition)->option('force'));
    }

    public function test_unknown_option_throws(): void
    {
        $this->expectException(InvalidInputException::class);
        $this->parser->parse(['--nope'], new InputDefinition());
    }

    public function test_option_missing_value_throws(): void
    {
        $definition = (new InputDefinition())->addOption(new OptionDefinition('path', 'p', true));

        $this->expectException(InvalidInputException::class);
        $this->parser->parse(['--path'], $definition);
    }
}
