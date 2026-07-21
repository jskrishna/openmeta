<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Unit;

use OpenMeta\Wordpress\Settings\SettingsAdapter;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class SettingsAdapterTest extends WordpressTestCase
{
    public function test_validated_settings_roundtrip(): void
    {
        $adapter = new SettingsAdapter($this->wp);
        $adapter->registerGroup('general', [
            'site_name' => 'required|string',
        ]);

        $saved = $adapter->save('general', ['site_name' => 'OpenMeta']);

        $this->assertSame(['site_name' => 'OpenMeta'], $saved);
        $this->assertSame(['site_name' => 'OpenMeta'], $adapter->load('general'));
        $this->assertSame(
            ['site_name' => 'OpenMeta'],
            $this->wp->options['openmeta_settings_general'] ?? null,
        );
    }

    public function test_unknown_group_throws(): void
    {
        $adapter = new SettingsAdapter($this->wp);

        $this->expectException(\InvalidArgumentException::class);
        $adapter->save('missing', ['foo' => 'bar']);
    }
}
