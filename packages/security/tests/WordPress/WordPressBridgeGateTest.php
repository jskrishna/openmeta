<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests\WordPress;

use OpenMeta\Security\Tests\SecurityTestCase;

/**
 * Phase 12 WordPress layer — Security stays WP-agnostic; bridges live in @openmeta/wordpress.
 */
final class WordPressBridgeGateTest extends SecurityTestCase
{
    public function test_security_src_has_no_wordpress_bridge_classes(): void
    {
        $root = dirname(__DIR__, 2) . '/src';
        $php = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($php as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $contents = (string) file_get_contents($file->getPathname());
            $this->assertStringNotContainsString(
                'function_exists(\'current_user_can\')',
                $contents,
                $file->getPathname()
            );
            $this->assertStringNotContainsString(
                'function_exists(\'wp_create_nonce\')',
                $contents,
                $file->getPathname()
            );
            $this->assertStringNotContainsString(
                'WordPressCapabilityChecker',
                $contents,
                $file->getPathname()
            );
            $this->assertStringNotContainsString(
                'WordPressNonceHandler',
                $contents,
                $file->getPathname()
            );
        }
    }
}
