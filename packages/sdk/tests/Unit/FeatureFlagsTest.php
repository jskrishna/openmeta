<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Tests\Unit;

use OpenMeta\Sdk\Support\FeatureFlags;
use OpenMeta\Sdk\Tests\SdkTestCase;

final class FeatureFlagsTest extends SdkTestCase
{
    public function test_defaults_to_disabled(): void
    {
        self::assertFalse((new FeatureFlags())->isEnabled('missing'));
    }

    public function test_enable_and_disable(): void
    {
        $flags = new FeatureFlags();
        $flags->enable('beta');
        self::assertTrue($flags->isEnabled('beta'));

        $flags->disable('beta');
        self::assertFalse($flags->isEnabled('beta'));
    }

    public function test_seed_namespaces_by_package(): void
    {
        $flags = new FeatureFlags();
        $flags->seed('acme/seo', ['sitemaps' => true, 'redirects' => false]);

        self::assertTrue($flags->isEnabled('acme/seo:sitemaps'));
        self::assertFalse($flags->isEnabled('acme/seo:redirects'));
    }
}
