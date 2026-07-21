<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Unit;

use OpenMeta\Wordpress\Meta\PostMetaAdapter;
use OpenMeta\Wordpress\Meta\UserMetaAdapter;
use OpenMeta\Wordpress\Runtime\ArrayWordPressRuntime;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class MetaAdapterTest extends WordpressTestCase
{
    public function test_post_meta_roundtrip_on_array_runtime(): void
    {
        $adapter = new PostMetaAdapter($this->wp);

        $this->assertTrue($adapter->update(42, 'openmeta_field', 'hello'));
        $this->assertSame('hello', $adapter->get(42, 'openmeta_field'));
        $this->assertTrue($adapter->delete(42, 'openmeta_field'));
        $this->assertSame('', $adapter->get(42, 'openmeta_field'));
    }

    public function test_user_meta_roundtrip_on_array_runtime(): void
    {
        $adapter = new UserMetaAdapter($this->wp);

        $this->assertTrue($adapter->update(7, 'profile', ['role' => 'editor']));
        $this->assertSame(['role' => 'editor'], $adapter->get(7, 'profile'));
    }

    public function test_array_runtime_records_post_meta_writes(): void
    {
        $adapter = new PostMetaAdapter($this->wp);
        $adapter->update(1, 'color', 'blue');

        $this->assertSame('blue', $this->wp->postMeta['object_1']['color'] ?? null);
    }
}
