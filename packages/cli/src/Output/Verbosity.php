<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Output;

/**
 * Output verbosity levels.
 */
enum Verbosity: int
{
    case Quiet = 0;
    case Normal = 1;
    case Verbose = 2;
    case Debug = 3;

    public function allows(self $level): bool
    {
        return $this->value >= $level->value;
    }
}
