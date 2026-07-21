<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

use OpenMeta\Cli\Input\InputDefinition;

/**
 * A console command.
 */
interface CommandInterface
{
    /**
     * The invocation name, e.g. "make:command".
     */
    public function name(): string;

    public function description(): string;

    /**
     * Declares the arguments and options this command accepts.
     */
    public function definition(): InputDefinition;

    /**
     * Run the command; return a process exit code.
     */
    public function execute(InputInterface $input, OutputInterface $output): int;
}
