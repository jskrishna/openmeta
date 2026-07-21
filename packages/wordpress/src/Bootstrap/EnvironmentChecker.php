<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Bootstrap;

use OpenMeta\Wordpress\Plugin\Requirements;

/**
 * Thin environment gate wrapping {@see Requirements}.
 */
final class EnvironmentChecker
{
    public function __construct(private readonly Requirements $requirements = new Requirements())
    {
    }

    /**
     * @return list<string>
     */
    public function failures(?string $phpVersion = null, ?string $wpVersion = null): array
    {
        return $this->requirements->check($phpVersion, $wpVersion);
    }

    public function passes(?string $phpVersion = null, ?string $wpVersion = null): bool
    {
        return $this->requirements->passes($phpVersion, $wpVersion);
    }
}
