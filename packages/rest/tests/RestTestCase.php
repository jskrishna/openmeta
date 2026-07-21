<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Tests;

use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Database\DatabaseServiceProvider;
use OpenMeta\Rest\RestKernel;
use OpenMeta\Rest\RestServiceProvider;
use OpenMeta\Rest\Router\Router;
use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Contracts\CapabilityCheckerInterface;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\SecurityServiceProvider;
use OpenMeta\Validation\ValidationServiceProvider;

abstract class RestTestCase extends \PHPUnit\Framework\TestCase
{
    protected Application $app;

    protected RestKernel $kernel;

    protected Router $router;

    protected ArrayCapabilityChecker $capabilities;

    protected Gate $gate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = Bootstrap::run(
            [
                'database' => [
                    'default' => 'memory',
                    'connections' => [
                        'memory' => ['driver' => 'memory', 'prefix' => 'om_'],
                    ],
                ],
            ],
            [
                ValidationServiceProvider::class,
                SecurityServiceProvider::class,
                DatabaseServiceProvider::class,
                RestServiceProvider::class,
            ]
        );

        $this->kernel = $this->app->get(RestKernel::class);
        $this->router = $this->app->get(Router::class);

        /** @var ArrayCapabilityChecker $caps */
        $caps = $this->app->get(CapabilityCheckerInterface::class);
        $this->capabilities = $caps;
        $this->gate = $this->app->get(Gate::class);
    }

    protected function grant(string ...$capabilities): void
    {
        $this->capabilities->grant($capabilities);
        $this->gate->flushCache();
    }
}
