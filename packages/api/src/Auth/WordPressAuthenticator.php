<?php

declare(strict_types=1);

namespace OpenMeta\Api\Auth;

use OpenMeta\Api\Exceptions\AuthenticationException;
use OpenMeta\Api\Rest\Request;

/**
 * WordPress auth bridge. Fail closed when WP user APIs are unavailable.
 */
final class WordPressAuthenticator implements AuthenticatorInterface
{
    public function authenticate(Request $request, bool $required): mixed
    {
        if (! function_exists('is_user_logged_in') || ! function_exists('wp_get_current_user')) {
            if ($required) {
                throw new AuthenticationException();
            }

            return null;
        }

        if (! is_user_logged_in()) {
            if ($required) {
                throw new AuthenticationException();
            }

            return null;
        }

        return wp_get_current_user();
    }
}
