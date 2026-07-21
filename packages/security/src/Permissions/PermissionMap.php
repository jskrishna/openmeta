<?php

declare(strict_types=1);

namespace OpenMeta\Security\Permissions;

/**
 * Permission → capability map (opaque capability strings).
 * WordPress maps these strings onto WP caps in `@openmeta/wordpress`.
 * Unknown permissions fail closed at the Gate.
 */
final class PermissionMap
{
    /** @var array<string, list<string>> */
    private array $map;

    /**
     * @param array<string, list<string>> $overrides
     */
    public function __construct(array $overrides = [])
    {
        $this->map = array_merge(self::defaults(), $overrides);
    }

    /**
     * @return array<string, list<string>>
     */
    public static function defaults(): array
    {
        return [
            Permission::MANAGE => ['manage_options'],
            Permission::MANAGE_FIELDS => ['manage_options'],
            Permission::MANAGE_SETTINGS => ['manage_options'],
            Permission::EDIT_CONTENT => ['edit_posts'],
            Permission::READ => ['read'],
        ];
    }

    /**
     * @param list<string> $capabilities
     */
    public function define(string $permission, array $capabilities): void
    {
        $this->map[$permission] = array_values($capabilities);
    }

    public function has(string $permission): bool
    {
        return isset($this->map[$permission]);
    }

    /**
     * @return list<string>
     */
    public function capabilitiesFor(string $permission): array
    {
        return $this->map[$permission] ?? [];
    }

    /**
     * @return array<string, list<string>>
     */
    public function all(): array
    {
        return $this->map;
    }
}
