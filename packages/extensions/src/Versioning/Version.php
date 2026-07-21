<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Versioning;

use OpenMeta\Extensions\Exceptions\InvalidVersionException;

/**
 * Immutable semantic version (major.minor.patch[-prerelease][+build]).
 *
 * Build metadata is ignored for ordering, per SemVer 2.0.0.
 */
final class Version
{
    private function __construct(
        public readonly int $major,
        public readonly int $minor,
        public readonly int $patch,
        public readonly string $prerelease = '',
    ) {
    }

    /**
     * Parse a version string. Accepts a leading "v", and missing minor/patch
     * components (treated as zero).
     *
     * @throws InvalidVersionException
     */
    public static function parse(string $version): self
    {
        $value = trim($version);

        if ($value === '') {
            throw InvalidVersionException::emptyVersion();
        }

        if ($value[0] === 'v' || $value[0] === 'V') {
            $value = substr($value, 1);
        }

        // Drop build metadata.
        $value = explode('+', $value, 2)[0];

        $prerelease = '';
        if (str_contains($value, '-')) {
            [$value, $prerelease] = explode('-', $value, 2);
        }

        $parts = explode('.', $value);
        $numbers = [];

        for ($i = 0; $i < 3; $i++) {
            $part = $parts[$i] ?? '0';

            if (preg_match('/^\d+$/', $part) !== 1) {
                throw InvalidVersionException::invalidVersion($version);
            }

            $numbers[] = (int) $part;
        }

        return new self($numbers[0], $numbers[1], $numbers[2], $prerelease);
    }

    public static function of(int $major, int $minor, int $patch, string $prerelease = ''): self
    {
        return new self($major, $minor, $patch, $prerelease);
    }

    /**
     * @return -1|0|1
     */
    public function compareTo(self $other): int
    {
        foreach (
            [
                [$this->major, $other->major],
                [$this->minor, $other->minor],
                [$this->patch, $other->patch],
            ] as [$a, $b]
        ) {
            if ($a !== $b) {
                return $a < $b ? -1 : 1;
            }
        }

        return $this->comparePrerelease($this->prerelease, $other->prerelease);
    }

    public function isPrerelease(): bool
    {
        return $this->prerelease !== '';
    }

    public function __toString(): string
    {
        $base = sprintf('%d.%d.%d', $this->major, $this->minor, $this->patch);

        return $this->prerelease === '' ? $base : $base . '-' . $this->prerelease;
    }

    /**
     * A release (no prerelease) outranks any prerelease of the same core.
     *
     * @return -1|0|1
     */
    private function comparePrerelease(string $a, string $b): int
    {
        if ($a === $b) {
            return 0;
        }

        if ($a === '') {
            return 1;
        }

        if ($b === '') {
            return -1;
        }

        $left = explode('.', $a);
        $right = explode('.', $b);
        $count = max(count($left), count($right));

        for ($i = 0; $i < $count; $i++) {
            if (! isset($left[$i])) {
                return -1;
            }

            if (! isset($right[$i])) {
                return 1;
            }

            $result = $this->compareIdentifier($left[$i], $right[$i]);

            if ($result !== 0) {
                return $result;
            }
        }

        return 0;
    }

    /**
     * @return -1|0|1
     */
    private function compareIdentifier(string $a, string $b): int
    {
        $aNumeric = preg_match('/^\d+$/', $a) === 1;
        $bNumeric = preg_match('/^\d+$/', $b) === 1;

        if ($aNumeric && $bNumeric) {
            $intA = (int) $a;
            $intB = (int) $b;

            return $intA === $intB ? 0 : ($intA < $intB ? -1 : 1);
        }

        // Numeric identifiers always have lower precedence than alphanumeric.
        if ($aNumeric) {
            return -1;
        }

        if ($bNumeric) {
            return 1;
        }

        return $a === $b ? 0 : ($a < $b ? -1 : 1);
    }
}
