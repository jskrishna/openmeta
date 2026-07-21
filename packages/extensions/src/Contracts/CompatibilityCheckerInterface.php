<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Contracts;

use OpenMeta\Extensions\Compatibility\CompatibilityReport;
use OpenMeta\Extensions\Compatibility\Environment;

/**
 * Validates an extension manifest against a runtime environment.
 */
interface CompatibilityCheckerInterface
{
    public function check(ManifestInterface $manifest, Environment $environment): CompatibilityReport;

    public function isCompatible(ManifestInterface $manifest, Environment $environment): bool;
}
