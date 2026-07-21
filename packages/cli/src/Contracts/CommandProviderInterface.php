<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

/**
 * A source of commands — the extension point third-party packages implement
 * to contribute commands without modifying framework code.
 */
interface CommandProviderInterface
{
    /**
     * @return iterable<CommandInterface>
     */
    public function commands(): iterable;
}
