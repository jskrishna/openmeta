<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Support;

use OpenMeta\Wordpress\Plugin\Requirements;

/**
 * PHP / WordPress version dependency checker.
 */
final class DependencyChecker
{
    public function __construct(private readonly Requirements $requirements = new Requirements())
    {
    }

    /**
     * @return list<string>
     */
    public function check(?string $phpVersion = null, ?string $wpVersion = null): array
    {
        return $this->requirements->check($phpVersion, $wpVersion);
    }

    public function passes(?string $phpVersion = null, ?string $wpVersion = null): bool
    {
        return $this->requirements->passes($phpVersion, $wpVersion);
    }
}
