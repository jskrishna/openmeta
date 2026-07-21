<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Capabilities;

use OpenMeta\Security\Contracts\CapabilityCheckerInterface;

/**
 * WordPress Roles & Capabilities bridge. Fail closed when WP is unavailable.
 */
final class WordPressCapabilityChecker implements CapabilityCheckerInterface
{
    public function can(string $capability): bool
    {
        if (! function_exists('current_user_can')) {
            return false;
        }

        return (bool) current_user_can($capability);
    }
}
