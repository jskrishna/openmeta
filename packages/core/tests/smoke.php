<?php

declare(strict_types=1);

/**
 * Milestone v0.1 smoke check — run: php packages/core/tests/smoke.php
 *
 * Flow: Bootstrap → Container → Config → Kernel → Service Provider → Done
 */

$root = dirname(__DIR__, 3);
require $root . '/vendor/autoload.php';

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Config\Repository;
use OpenMeta\Core\Tests\Fixtures\SmokeServiceProvider;

$kernel = Bootstrap::init(
    [
        'app' => [
            'name' => 'OpenMeta',
            'env' => 'testing',
        ],
    ],
    [SmokeServiceProvider::class]
);

$config = $kernel->container()->get(Repository::class);
assert($kernel->isBooted() === true, 'Kernel should be booted');
assert($config->get('app.name') === 'OpenMeta', 'Config app.name mismatch');
assert($kernel->container()->get('smoke.flag') === 'registered', 'Provider register failed');
assert($kernel->container()->get('smoke.booted')->value === 'booted', 'Provider boot failed');
assert(Bootstrap::VERSION === '0.1.0-alpha', 'Version mismatch');

fwrite(STDOUT, 'OK packages/core smoke (' . Bootstrap::VERSION . ')' . PHP_EOL);
exit(0);
