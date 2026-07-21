<?php

declare(strict_types=1);

/**
 * Configuration unit smoke: files + repository.
 *
 * Run: php packages/core/tests/Unit/configuration.php
 */

$root = dirname(__DIR__, 4);
require $root . '/vendor/autoload.php';

use OpenMeta\Core\Bootstrap\Bootstrapper;
use OpenMeta\Core\Config\ConfigLoader;
use OpenMeta\Core\Config\ConfigRepository;
use OpenMeta\Core\Contracts\ConfigRepositoryInterface;

$path = Bootstrapper::defaultConfigPath();
$loaded = ConfigLoader::load($path);

assert(isset($loaded['app'], $loaded['database'], $loaded['api']), 'Expected app/database/api config files');
assert($loaded['app']['name'] === 'OpenMeta', 'app.name default');
assert($loaded['database']['default'] === 'wordpress', 'database.default placeholder');
assert($loaded['api']['enabled'] === false, 'api.enabled placeholder');

$config = ConfigRepository::load($path, [
    'app' => [
        'env' => 'testing',
        'debug' => true,
    ],
]);

assert($config instanceof ConfigRepositoryInterface);
assert($config->get('app.name') === 'OpenMeta', 'Defaults preserved');
assert($config->get('app.env') === 'testing', 'Override merged');
assert($config->get('app.debug') === true, 'Override merged');
assert($config->get('api.prefix') === 'openmeta/v1', 'Sibling file intact');
assert($config->has('database.connections.wordpress.driver') === true, 'Nested has()');

$config->set('app.name', 'OpenMeta Test');
assert($config->get('app.name') === 'OpenMeta Test', 'set() works');

$app = Bootstrapper::boot(['app' => ['env' => 'testing']]);
assert($app->config()->get('app.env') === 'testing', 'Bootstrap merges overrides');
assert($app->config()->get('database.default') === 'wordpress', 'Bootstrap loads defaults');
assert($app->get('config') === $app->config(), 'config alias resolves');

fwrite(STDOUT, "OK Configuration — app / database / api\n");
exit(0);
