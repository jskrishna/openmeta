<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests\WordPress;

use OpenMeta\Api\Auth\WordPressAuthenticator;
use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Tests\ApiTestCase;

final class WordPressAuthGateTest extends ApiTestCase
{
    public function test_wp_authenticator_fails_closed_without_wp(): void
    {
        if (function_exists('is_user_logged_in')) {
            $this->markTestSkipped('WordPress is loaded.');
        }

        $auth = new WordPressAuthenticator();
        $this->expectException(\OpenMeta\Api\Exceptions\AuthenticationException::class);
        $auth->authenticate(new Request('GET', '/x'), true);
    }
}
