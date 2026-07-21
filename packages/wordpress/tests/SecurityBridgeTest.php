<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests;

use OpenMeta\Wordpress\Capabilities\WordPressCapabilityChecker;
use OpenMeta\Wordpress\Nonce\WordPressNonceHandler;
use PHPUnit\Framework\TestCase;

final class SecurityBridgeTest extends TestCase
{
    public function test_capability_checker_fails_closed_without_wp(): void
    {
        if (function_exists('current_user_can')) {
            self::markTestSkipped('WordPress is loaded in this environment.');
        }

        self::assertFalse((new WordPressCapabilityChecker())->can('manage_options'));
    }

    public function test_nonce_handler_fails_closed_without_wp(): void
    {
        if (function_exists('wp_create_nonce')) {
            self::markTestSkipped('WordPress is loaded in this environment.');
        }

        $handler = new WordPressNonceHandler();
        self::assertSame('', $handler->create('action'));
        self::assertFalse($handler->verify('x', 'action'));
    }
}
