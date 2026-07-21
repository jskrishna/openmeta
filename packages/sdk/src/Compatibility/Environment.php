<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Compatibility;

/**
 * A snapshot of the runtime the SDK is evaluating extensions against.
 */
final class Environment
{
    /**
     * @param list<string>          $loadedPhpExtensions PHP extensions currently loaded
     * @param array<string, string> $installedExtensions packageId => installed version
     */
    public function __construct(
        public readonly string $coreVersion,
        public readonly string $phpVersion,
        public readonly ?string $wordpressVersion = null,
        public readonly array $loadedPhpExtensions = [],
        public readonly array $installedExtensions = [],
    ) {
    }

    /**
     * Build an environment from the current PHP runtime.
     *
     * @param array<string, string> $installedExtensions
     */
    public static function detect(
        string $coreVersion,
        ?string $wordpressVersion = null,
        array $installedExtensions = [],
    ): self {
        return new self(
            $coreVersion,
            PHP_VERSION,
            $wordpressVersion,
            get_loaded_extensions(),
            $installedExtensions,
        );
    }

    public function hasPhpExtension(string $name): bool
    {
        return in_array($name, $this->loadedPhpExtensions, true);
    }

    public function installedVersion(string $packageId): ?string
    {
        return $this->installedExtensions[$packageId] ?? null;
    }

    /**
     * Return a copy with additional installed extensions merged in.
     *
     * Used during bootstrap so an extension whose dependency is also being
     * installed in the same pass is treated as satisfiable.
     *
     * @param array<string, string> $extensions packageId => version
     */
    public function withInstalled(array $extensions): self
    {
        return new self(
            $this->coreVersion,
            $this->phpVersion,
            $this->wordpressVersion,
            $this->loadedPhpExtensions,
            array_merge($this->installedExtensions, $extensions),
        );
    }
}
