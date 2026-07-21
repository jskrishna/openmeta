<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Support;

use OpenMeta\Cli\Contracts\CommandProviderInterface;
use OpenMeta\Generator\Contracts\GeneratorManagerInterface;

/**
 * Exposes every registered generator as a `make:<key>` console command via the
 * CLI's {@see CommandProviderInterface} extension point.
 */
final class GeneratorCommandProvider implements CommandProviderInterface
{
    public function __construct(private readonly GeneratorManagerInterface $manager)
    {
    }

    public function commands(): iterable
    {
        foreach ($this->manager->generators()->all() as $generator) {
            yield new GeneratorCommand(
                $generator->key(),
                'Generate ' . $generator->description() . '.',
                $this->manager,
            );
        }
    }
}
