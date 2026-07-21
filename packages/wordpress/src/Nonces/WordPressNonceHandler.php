<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Nonces;

use OpenMeta\Security\Contracts\NonceHandlerInterface;

/**
 * WordPress nonce bridge. Fail closed when WP nonce APIs are unavailable.
 */
final class WordPressNonceHandler implements NonceHandlerInterface
{
    public function create(string $action): string
    {
        if (! function_exists('wp_create_nonce')) {
            return '';
        }

        return (string) wp_create_nonce($action);
    }

    public function verify(string $nonce, string $action): bool
    {
        if (! function_exists('wp_verify_nonce') || $nonce === '') {
            return false;
        }

        return (int) wp_verify_nonce($nonce, $action) > 0;
    }
}
