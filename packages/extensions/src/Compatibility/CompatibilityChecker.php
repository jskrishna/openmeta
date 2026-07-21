<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Compatibility;

use OpenMeta\Extensions\Contracts\CompatibilityCheckerInterface;
use OpenMeta\Extensions\Contracts\ManifestInterface;
use OpenMeta\Extensions\Contracts\VersionComparatorInterface;
use OpenMeta\Extensions\Exceptions\InvalidVersionException;

/**
 * Default compatibility checker.
 *
 * Validates, in order: framework (core) version window, PHP version,
 * WordPress version, required PHP extensions, and required OpenMeta
 * extensions (presence + version constraint).
 */
final class CompatibilityChecker implements CompatibilityCheckerInterface
{
    public function __construct(
        private readonly VersionComparatorInterface $versions,
    ) {
    }

    public function check(ManifestInterface $manifest, Environment $environment): CompatibilityReport
    {
        $issues = [];

        $this->checkCoreWindow($manifest, $environment, $issues);
        $this->checkRequirements($manifest, $environment, $issues);
        $this->checkDependencies($manifest, $environment, $issues);

        return CompatibilityReport::fromIssues($issues);
    }

    public function isCompatible(ManifestInterface $manifest, Environment $environment): bool
    {
        return $this->check($manifest, $environment)->compatible;
    }

    /**
     * @param list<string> $issues
     */
    private function checkCoreWindow(ManifestInterface $manifest, Environment $environment, array &$issues): void
    {
        $min = $manifest->minimumCoreVersion();
        $max = $manifest->maximumCoreVersion();

        if ($min !== null && $this->safeCompare($environment->coreVersion, $min) < 0) {
            $issues[] = sprintf(
                'Requires OpenMeta >= %s, found %s.',
                $min,
                $environment->coreVersion
            );
        }

        if ($max !== null && $this->safeCompare($environment->coreVersion, $max) > 0) {
            $issues[] = sprintf(
                'Requires OpenMeta <= %s, found %s.',
                $max,
                $environment->coreVersion
            );
        }
    }

    /**
     * @param list<string> $issues
     */
    private function checkRequirements(ManifestInterface $manifest, Environment $environment, array &$issues): void
    {
        $requirements = $manifest->requirements();

        if ($requirements->php !== null && ! $this->safeSatisfies($environment->phpVersion, $requirements->php)) {
            $issues[] = sprintf(
                'Requires PHP %s, found %s.',
                $requirements->php,
                $environment->phpVersion
            );
        }

        if ($requirements->requiresWordpress()) {
            $wp = $environment->wordpressVersion;
            $constraint = (string) $requirements->wordpress;

            if ($wp === null) {
                $issues[] = sprintf('Requires WordPress %s, but no WordPress runtime is present.', $constraint);
            } elseif (! $this->safeSatisfies($wp, $constraint)) {
                $issues[] = sprintf('Requires WordPress %s, found %s.', $constraint, $wp);
            }
        }

        foreach ($requirements->phpExtensions as $extension) {
            if (! $environment->hasPhpExtension($extension)) {
                $issues[] = sprintf('Requires PHP extension [%s], which is not loaded.', $extension);
            }
        }
    }

    /**
     * @param list<string> $issues
     */
    private function checkDependencies(ManifestInterface $manifest, Environment $environment, array &$issues): void
    {
        foreach ($manifest->dependencies() as $dependency) {
            $installed = $environment->installedVersion($dependency->packageId);

            if ($installed === null) {
                if ($dependency->isRequired()) {
                    $issues[] = sprintf('Requires extension [%s], which is not installed.', $dependency->packageId);
                }

                continue;
            }

            if (! $this->safeSatisfies($installed, $dependency->constraint)) {
                $issues[] = sprintf(
                    'Requires [%s] matching [%s], found %s.',
                    $dependency->packageId,
                    $dependency->constraint,
                    $installed
                );
            }
        }
    }

    private function safeCompare(string $a, string $b): int
    {
        try {
            return $this->versions->compare($a, $b);
        } catch (InvalidVersionException) {
            // Unparseable versions are treated as equal so they do not spuriously fail.
            return 0;
        }
    }

    private function safeSatisfies(string $version, string $constraint): bool
    {
        try {
            return $this->versions->satisfies($version, $constraint);
        } catch (InvalidVersionException) {
            return false;
        }
    }
}
