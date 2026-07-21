<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Unit;

use OpenMeta\Cli\Exceptions\CommandNotFoundException;
use OpenMeta\Cli\Registry\CommandRegistry;
use OpenMeta\Cli\Tests\Fixtures\RecordingCommand;
use PHPUnit\Framework\TestCase;

final class CommandRegistryTest extends TestCase
{
    public function test_register_and_get(): void
    {
        $registry = new CommandRegistry();
        $registry->register(new RecordingCommand('greet'));

        self::assertTrue($registry->has('greet'));
        self::assertSame('greet', $registry->get('greet')->name());
        self::assertArrayHasKey('greet', $registry->descriptions());
    }

    public function test_lazy_command_resolves_once(): void
    {
        $registry = new CommandRegistry();
        $calls = 0;
        $registry->registerLazy('slow', 'A lazy command', function () use (&$calls): RecordingCommand {
            $calls++;

            return new RecordingCommand('slow');
        });

        self::assertTrue($registry->has('slow'));
        self::assertSame(0, $calls, 'factory must not run until resolved');

        $registry->get('slow');
        $registry->get('slow');

        self::assertSame(1, $calls, 'factory resolves exactly once');
    }

    public function test_unknown_command_throws(): void
    {
        $this->expectException(CommandNotFoundException::class);
        (new CommandRegistry())->get('nope');
    }

    public function test_names_are_sorted(): void
    {
        $registry = new CommandRegistry();
        $registry->register(new RecordingCommand('zeta'));
        $registry->register(new RecordingCommand('alpha'));

        self::assertSame(['alpha', 'zeta'], $registry->names());
    }
}
