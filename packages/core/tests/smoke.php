<?php

declare(strict_types=1);

/**
 * Core Bootstrap v0.1.0-alpha smoke test.
 *
 * Goal: Application → Kernel → Container → Service Providers → Configuration → Framework Booted
 *
 * Run: composer test:core
 */

$root = dirname(__DIR__, 3);
require $root . '/vendor/autoload.php';

use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Config\ConfigRepository;
use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Kernel\Kernel;
use OpenMeta\Core\Tests\Fixtures\SmokeServiceProvider;

$app = Bootstrap::run(
    [
        'app' => [
            'name' => 'OpenMeta',
            'env' => 'testing',
            'version' => '0.1.0-alpha',
        ],
    ],
    [SmokeServiceProvider::class]
);

assert($app instanceof Application, 'Expected Application');
assert($app->isBooted() === true, 'Framework should be booted');
assert($app->kernel()->isReady() === true, 'Kernel should be Ready');
assert($app->kernel()->phase()->value === 'ready', 'Kernel phase should be ready');
assert($app->version() === '0.1.0-alpha', 'Version mismatch');
assert($app->kernel() instanceof Kernel, 'Kernel missing');
assert($app->container() instanceof Container, 'Container missing');
assert($app->config() instanceof ConfigRepository, 'Configuration missing');
assert($app->config()->get('app.name') === 'OpenMeta', 'Config not loaded');
assert($app->config()->get('app.env') === 'testing', 'Config override not merged');
assert($app->config()->has('database.default') === true, 'Default config files not loaded');
assert($app->config()->has('api.prefix') === true, 'api.php not loaded');
assert($app->get(Application::class) === $app, 'Application not in container');
assert($app->get(Kernel::class) === $app->kernel(), 'Kernel not in container');
assert($app->get('smoke.flag') === 'registered', 'Service provider register failed');
assert($app->get('smoke.booted')->value === 'booted', 'Service provider boot failed');
assert($app->get('smoke.framework_booted') === true, 'FrameworkBooted not observed');

fwrite(STDOUT, "OK Core Bootstrap v0.1.0-alpha — Framework Booted\n");
exit(0);
