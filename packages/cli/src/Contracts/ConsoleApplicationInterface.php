<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

/**
 * Public façade for the console application.
 */
interface ConsoleApplicationInterface
{
    public function register(CommandInterface $command): void;

    /**
     * Run against a raw argv list; returns the process exit code.
     *
     * @param list<string> $argv Tokens after the script name
     */
    public function run(array $argv): int;

    public function registry(): CommandRegistryInterface;
}
