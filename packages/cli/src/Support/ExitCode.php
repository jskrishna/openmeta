<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Support;

/**
 * Conventional process exit codes.
 */
final class ExitCode
{
    public const SUCCESS = 0;

    public const FAILURE = 1;

    public const INVALID = 2;

    private function __construct()
    {
    }
}
