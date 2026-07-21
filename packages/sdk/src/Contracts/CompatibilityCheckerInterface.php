<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Contracts;

use OpenMeta\Sdk\Compatibility\CompatibilityReport;
use OpenMeta\Sdk\Compatibility\Environment;

/**
 * Validates an extension manifest against a runtime environment.
 */
interface CompatibilityCheckerInterface
{
    public function check(ManifestInterface $manifest, Environment $environment): CompatibilityReport;

    public function isCompatible(ManifestInterface $manifest, Environment $environment): bool;
}
