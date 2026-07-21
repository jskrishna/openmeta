<?php

declare(strict_types=1);

/**
 * Application entry-point smoke.
 *
 * Run: php packages/core/tests/Unit/application.php
 */

$root = dirname(__DIR__, 4);
require $root . '/vendor/autoload.php';

use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Bootstrap\Bootstrapper;
use OpenMeta\Core\Contracts\ApplicationInterface;
use OpenMeta\Core\Tests\Fixtures\SmokeServiceProvider;

$app = Application::boot(
    ['app' => ['env' => 'testing']],
    [SmokeServiceProvider::class]
);

assert($app instanceof ApplicationInterface);
assert($app->isBooted() === true, 'Application should be booted');
assert($app->container() === $app->get(Application::class)->container(), 'Holds container');
assert($app->config()->get('app.env') === 'testing', 'Config loaded');
assert($app->kernel()->isBooted() === true, 'Kernel started and booted');
assert($app->get('smoke.flag') === 'registered', 'Providers registered');
assert($app->get('smoke.booted')->value === 'booted', 'Providers booted');

$viaBootstrapper = Bootstrapper::boot(['app' => ['env' => 'testing']]);
assert($viaBootstrapper instanceof ApplicationInterface);
assert($viaBootstrapper->isBooted() === true, 'Bootstrapper alias works');

fwrite(STDOUT, "OK Application — main entry point\n");
exit(0);
