<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Versioning;

use OpenMeta\Sdk\Exceptions\InvalidVersionException;

/**
 * A parsed version constraint.
 *
 * Supported grammar:
 *   - "*" or ""            → any version
 *   - "1.2.3"              → exact match
 *   - ">= > <= < = "       → comparison operators
 *   - "^1.2.3"             → caret range (compatible within the leftmost non-zero)
 *   - "~1.2" / "~1.2.3"    → tilde range (patch/minor flexible)
 *   - "&gt;=1.0 &lt;2.0"   → AND (space or comma separated)
 *   - "1.x || 2.x"         → OR (double pipe)
 */
final class VersionConstraint
{
    /**
     * OR of AND-groups. Each comparator is [op, Version].
     *
     * @param list<list<array{op: string, version: Version}>> $groups
     */
    private function __construct(
        private readonly array $groups,
        private readonly bool $matchesAny,
    ) {
    }

    /**
     * @throws InvalidVersionException
     */
    public static function parse(string $constraint): self
    {
        $value = trim($constraint);

        if ($value === '' || $value === '*') {
            return new self([], true);
        }

        $groups = [];

        foreach (preg_split('/\s*\|\|\s*/', $value) ?: [] as $orExpression) {
            $group = self::parseAndGroup($orExpression, $constraint);

            if ($group !== []) {
                $groups[] = $group;
            }
        }

        if ($groups === []) {
            throw InvalidVersionException::invalidConstraint($constraint);
        }

        return new self($groups, false);
    }

    public function allows(Version $version): bool
    {
        if ($this->matchesAny) {
            return true;
        }

        foreach ($this->groups as $group) {
            if ($this->satisfiesGroup($version, $group)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<array{op: string, version: Version}> $group
     */
    private function satisfiesGroup(Version $version, array $group): bool
    {
        foreach ($group as $comparator) {
            if (! $this->satisfiesComparator($version, $comparator['op'], $comparator['version'])) {
                return false;
            }
        }

        return true;
    }

    private function satisfiesComparator(Version $version, string $op, Version $bound): bool
    {
        $comparison = $version->compareTo($bound);

        return match ($op) {
            '=' => $comparison === 0,
            '>' => $comparison > 0,
            '>=' => $comparison >= 0,
            '<' => $comparison < 0,
            '<=' => $comparison <= 0,
            default => false,
        };
    }

    /**
     * @return list<array{op: string, version: Version}>
     *
     * @throws InvalidVersionException
     */
    private static function parseAndGroup(string $expression, string $original): array
    {
        $tokens = preg_split('/[\s,]+/', trim($expression)) ?: [];
        $comparators = [];

        foreach ($tokens as $token) {
            if ($token === '') {
                continue;
            }

            foreach (self::parseToken($token, $original) as $comparator) {
                $comparators[] = $comparator;
            }
        }

        return $comparators;
    }

    /**
     * @return list<array{op: string, version: Version}>
     *
     * @throws InvalidVersionException
     */
    private static function parseToken(string $token, string $original): array
    {
        if (str_starts_with($token, '^')) {
            return self::caret(substr($token, 1));
        }

        if (str_starts_with($token, '~')) {
            return self::tilde(substr($token, 1));
        }

        if (preg_match('/^(>=|<=|>|<|=)?\s*(.+)$/', $token, $matches) !== 1) {
            throw InvalidVersionException::invalidConstraint($original);
        }

        $op = $matches[1] === '' ? '=' : $matches[1];

        return [['op' => $op, 'version' => Version::parse($matches[2])]];
    }

    /**
     * @return list<array{op: string, version: Version}>
     */
    private static function caret(string $raw): array
    {
        $lower = Version::parse($raw);

        if ($lower->major > 0) {
            $upper = Version::of($lower->major + 1, 0, 0);
        } elseif ($lower->minor > 0) {
            $upper = Version::of(0, $lower->minor + 1, 0);
        } else {
            $upper = Version::of(0, 0, $lower->patch + 1);
        }

        return [
            ['op' => '>=', 'version' => $lower],
            ['op' => '<', 'version' => $upper],
        ];
    }

    /**
     * @return list<array{op: string, version: Version}>
     */
    private static function tilde(string $raw): array
    {
        $components = count(explode('.', trim(explode('-', $raw)[0])));
        $lower = Version::parse($raw);

        // "~1"        → >=1.0.0 <2.0.0
        // "~1.2"/"~1.2.3" → >=x.y.z <x.(y+1).0
        $upper = $components < 2
            ? Version::of($lower->major + 1, 0, 0)
            : Version::of($lower->major, $lower->minor + 1, 0);

        return [
            ['op' => '>=', 'version' => $lower],
            ['op' => '<', 'version' => $upper],
        ];
    }
}
