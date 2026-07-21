<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests;

use OpenMeta\Api\Auth\WordPressAuthenticator;
use OpenMeta\Api\Exceptions\AuthenticationException;
use OpenMeta\Api\Rest\Request;

final class WordPressAuthBridgeTest extends \PHPUnit\Framework\TestCase
{
    public function test_wordpress_authenticator_fails_closed_without_wp(): void
    {
        if (function_exists('is_user_logged_in')) {
            self::markTestSkipped('WordPress is loaded.');
        }

        $auth = new WordPressAuthenticator();
        self::assertNull($auth->authenticate(new Request('GET', '/'), false));

        $this->expectException(AuthenticationException::class);
        $auth->authenticate(new Request('GET', '/'), true);
    }
}
