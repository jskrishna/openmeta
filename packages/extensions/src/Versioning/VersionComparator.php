<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Versioning;

use OpenMeta\Extensions\Contracts\VersionComparatorInterface;

/**
 * Default SemVer comparator built on {@see Version} and {@see VersionConstraint}.
 */
final class VersionComparator implements VersionComparatorInterface
{
    public function compare(string $a, string $b): int
    {
        return Version::parse($a)->compareTo(Version::parse($b));
    }

    public function satisfies(string $version, string $constraint): bool
    {
        return VersionConstraint::parse($constraint)->allows(Version::parse($version));
    }
}
