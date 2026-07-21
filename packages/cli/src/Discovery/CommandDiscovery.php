<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Discovery;

use OpenMeta\Cli\Contracts\CommandProviderInterface;
use OpenMeta\Cli\Contracts\CommandRegistryInterface;

/**
 * Populates a command registry from providers or explicit command lists —
 * the mechanism third-party packages use to contribute commands.
 */
final class CommandDiscovery
{
    public function __construct(private readonly CommandRegistryInterface $registry)
    {
    }

    /**
     * @param iterable<CommandProviderInterface> $providers
     */
    public function fromProviders(iterable $providers): void
    {
        foreach ($providers as $provider) {
            foreach ($provider->commands() as $command) {
                $this->registry->register($command);
            }
        }
    }

    /**
     * @param iterable<\OpenMeta\Cli\Contracts\CommandInterface> $commands
     */
    public function register(iterable $commands): void
    {
        foreach ($commands as $command) {
            $this->registry->register($command);
        }
    }
}
