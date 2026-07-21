<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Versioning;

use OpenMeta\Sdk\Contracts\VersionComparatorInterface;

/**
 * Tracks installed extension versions and answers update questions.
 *
 * Pure book-keeping — it never performs downloads or auto-updates
 * (those are explicitly out of scope for the SDK).
 */
final class VersionManager
{
    /** @var array<string, string> packageId => installed version */
    private array $installed = [];

    public function __construct(
        private readonly VersionComparatorInterface $comparator,
    ) {
    }

    public function record(string $packageId, string $version): void
    {
        $this->installed[$packageId] = $version;
    }

    public function forget(string $packageId): void
    {
        unset($this->installed[$packageId]);
    }

    public function installed(string $packageId): ?string
    {
        return $this->installed[$packageId] ?? null;
    }

    /**
     * Whether $candidate is a newer version than what is currently installed.
     */
    public function hasUpdate(string $packageId, string $candidate): bool
    {
        $current = $this->installed[$packageId] ?? null;

        if ($current === null) {
            return false;
        }

        return $this->comparator->compare($candidate, $current) > 0;
    }

    public function satisfies(string $packageId, string $constraint): bool
    {
        $current = $this->installed[$packageId] ?? null;

        if ($current === null) {
            return false;
        }

        return $this->comparator->satisfies($current, $constraint);
    }

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        return $this->installed;
    }
}
