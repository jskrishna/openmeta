<?php

declare(strict_types=1);

namespace OpenMeta\Security\Permissions;

/**
 * Stable OpenMeta permission ids. Map to host capabilities via {@see PermissionMap}.
 */
final class Permission
{
    public const MANAGE = 'openmeta.manage';

    public const MANAGE_FIELDS = 'openmeta.manage_fields';

    public const MANAGE_SETTINGS = 'openmeta.manage_settings';

    public const EDIT_CONTENT = 'openmeta.edit_content';

    public const READ = 'openmeta.read';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::MANAGE,
            self::MANAGE_FIELDS,
            self::MANAGE_SETTINGS,
            self::EDIT_CONTENT,
            self::READ,
        ];
    }
}
