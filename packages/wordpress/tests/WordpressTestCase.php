<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests;

use OpenMeta\Wordpress\Plugin\Plugin;
use OpenMeta\Wordpress\Plugin\Requirements;
use OpenMeta\Wordpress\Runtime\ArrayWordPressRuntime;

abstract class WordpressTestCase extends \PHPUnit\Framework\TestCase
{
    protected ArrayWordPressRuntime $wp;

    protected Plugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->wp = new ArrayWordPressRuntime();
        $this->plugin = new Plugin($this->wp, new Requirements(), dirname(__DIR__, 3) . '/openmeta.php');
    }

    /**
     * @return array<string, mixed>
     */
    protected function testConfig(): array
    {
        return [
            'app' => ['key' => 'wordpress-test-secret'],
            'database' => [
                'default' => 'memory',
                'connections' => [
                    'memory' => ['driver' => 'memory', 'prefix' => 'om_'],
                ],
            ],
        ];
    }
}
