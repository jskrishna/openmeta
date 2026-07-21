<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Configuration;

use OpenMeta\Cli\Exceptions\CliException;
use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Loads CLI configuration from arrays or JSON files.
 */
final class ConfigurationLoader
{
    public function __construct(private readonly FilesystemInterface $filesystem)
    {
    }

    /**
     * @param array<string, mixed> $items
     */
    public function fromArray(array $items): Configuration
    {
        return new Configuration($items);
    }

    /**
     * @throws CliException
     */
    public function fromJsonFile(string $path): Configuration
    {
        if (! $this->filesystem->isFile($path)) {
            throw new CliException(sprintf('Configuration file [%s] does not exist.', $path));
        }

        /** @var mixed $decoded */
        $decoded = json_decode($this->filesystem->get($path), true);

        if (! is_array($decoded)) {
            throw new CliException(sprintf('Configuration file [%s] is not valid JSON.', $path));
        }

        /** @var array<string, mixed> $decoded */
        return new Configuration($decoded);
    }
}
