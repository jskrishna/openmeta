<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests;

use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Config\ConfigLoader;
use OpenMeta\Core\Config\ConfigRepository;
use OpenMeta\Core\Contracts\ConfigRepositoryInterface;

final class ConfigTest extends CoreTestCase
{
    public function test_repository_implements_contract(): void
    {
        $config = new ConfigRepository(['app' => ['name' => 'OpenMeta']]);

        self::assertInstanceOf(ConfigRepositoryInterface::class, $config);
    }

    public function test_loader_reads_default_config_files(): void
    {
        $loaded = ConfigLoader::load(Application::defaultConfigPath());

        self::assertArrayHasKey('app', $loaded);
        self::assertArrayHasKey('database', $loaded);
        self::assertArrayHasKey('api', $loaded);
        self::assertSame('OpenMeta', $loaded['app']['name']);
        self::assertSame('memory', $loaded['database']['default']);
        self::assertFalse($loaded['api']['enabled']);
    }

    public function test_load_merges_overrides(): void
    {
        $config = ConfigRepository::load(Application::defaultConfigPath(), [
            'app' => [
                'env' => 'testing',
                'debug' => true,
            ],
        ]);

        self::assertSame('OpenMeta', $config->get('app.name'));
        self::assertSame('testing', $config->get('app.env'));
        self::assertTrue($config->get('app.debug'));
        self::assertSame('openmeta/v1', $config->get('api.prefix'));
    }

    public function test_dot_notation_get_set_has(): void
    {
        $config = new ConfigRepository();

        self::assertFalse($config->has('app.name'));

        $config->set('app.name', 'OpenMeta');

        self::assertTrue($config->has('app.name'));
        self::assertSame('OpenMeta', $config->get('app.name'));
        self::assertSame('fallback', $config->get('missing', 'fallback'));
    }

    public function test_merge_replaces_nested_values(): void
    {
        $config = new ConfigRepository([
            'app' => ['name' => 'OpenMeta', 'env' => 'production'],
        ]);

        $config->merge(['app' => ['env' => 'testing']]);

        self::assertSame('OpenMeta', $config->get('app.name'));
        self::assertSame('testing', $config->get('app.env'));
    }

    public function test_all_returns_tree(): void
    {
        $items = ['app' => ['name' => 'OpenMeta']];
        $config = new ConfigRepository($items);

        self::assertSame($items, $config->all());
    }
}
