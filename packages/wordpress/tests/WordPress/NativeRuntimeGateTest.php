<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\WordPress;

use OpenMeta\Wordpress\Runtime\NativeWordPressRuntime;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class NativeRuntimeGateTest extends WordpressTestCase
{
    public function test_native_runtime_safe_without_wordpress(): void
    {
        $native = new NativeWordPressRuntime();
        $this->assertFalse($native->isLoaded());
        $this->assertFalse($native->registerRestRoute('openmeta/v1', '/health', []));
    }
}
