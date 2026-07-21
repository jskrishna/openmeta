<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Unit;

use OpenMeta\Cli\Commands\MakeCommandCommand;
use OpenMeta\Cli\Input\Input;
use OpenMeta\Cli\Output\BufferedOutput;
use OpenMeta\Cli\Output\OutputFormatter;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Cli\Support\StubGenerator;
use OpenMeta\Cli\Tests\Fixtures\InMemoryFilesystem;
use PHPUnit\Framework\TestCase;

final class MakeCommandTest extends TestCase
{
    private function buffer(): BufferedOutput
    {
        return new BufferedOutput(new OutputFormatter(false));
    }

    public function test_generates_a_command_stub(): void
    {
        $filesystem = new InMemoryFilesystem();
        $command = new MakeCommandCommand(new StubGenerator($filesystem));

        $exit = $command->execute(new Input(['name' => 'GreetCommand'], ['path' => null]), $this->buffer());

        self::assertSame(ExitCode::SUCCESS, $exit);
        self::assertTrue($filesystem->isFile('src/Commands/GreetCommand.php'));
        $contents = $filesystem->get('src/Commands/GreetCommand.php');
        self::assertStringContainsString('final class GreetCommand', $contents);
        self::assertStringContainsString("\$name = 'greet'", $contents);
    }

    public function test_respects_custom_path(): void
    {
        $filesystem = new InMemoryFilesystem();
        $command = new MakeCommandCommand(new StubGenerator($filesystem));

        $command->execute(new Input(['name' => 'PingCommand'], ['path' => 'app/Ping.php']), $this->buffer());

        self::assertTrue($filesystem->isFile('app/Ping.php'));
    }

    public function test_missing_name_is_invalid(): void
    {
        $command = new MakeCommandCommand(new StubGenerator(new InMemoryFilesystem()));

        $exit = $command->execute(new Input(['name' => ''], ['path' => null]), $this->buffer());

        self::assertSame(ExitCode::INVALID, $exit);
    }

    public function test_existing_file_is_not_overwritten(): void
    {
        $filesystem = new InMemoryFilesystem(['src/Commands/GreetCommand.php' => '<?php // existing']);
        $command = new MakeCommandCommand(new StubGenerator($filesystem));

        $exit = $command->execute(new Input(['name' => 'GreetCommand'], ['path' => null]), $this->buffer());

        self::assertSame(ExitCode::FAILURE, $exit);
        self::assertSame('<?php // existing', $filesystem->get('src/Commands/GreetCommand.php'));
    }
}
