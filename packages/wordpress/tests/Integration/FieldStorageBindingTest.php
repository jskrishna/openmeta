<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Integration;

use OpenMeta\Fields\Contracts\FieldStorageInterface;
use OpenMeta\Fields\Storage\StorageRegistry;
use OpenMeta\Wordpress\Meta\WordPressFieldStorage;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class FieldStorageBindingTest extends WordpressTestCase
{
    public function test_boot_binds_wordpress_field_storage(): void
    {
        $app = $this->plugin->boot($this->testConfig());

        $this->assertNotNull($app);

        $storage = $app->get(FieldStorageInterface::class);
        $this->assertInstanceOf(WordPressFieldStorage::class, $storage);

        $registry = $app->get(StorageRegistry::class);
        $this->assertSame($storage, $registry->get('wordpress'));
    }
}
