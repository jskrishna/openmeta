<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests;

use OpenMeta\Api\Auth\AuthenticatorInterface;
use OpenMeta\Api\Auth\TokenAuthenticator;
use OpenMeta\Api\Rest\RestKernel;
use OpenMeta\Api\ApiServiceProvider;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Database\DatabaseServiceProvider;
use OpenMeta\Fields\FieldsServiceProvider;
use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Contracts\CapabilityCheckerInterface;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\SecurityServiceProvider;
use OpenMeta\Validation\ValidationServiceProvider;

abstract class ApiTestCase extends \PHPUnit\Framework\TestCase
{
    protected RestKernel $api;

    protected TokenAuthenticator $auth;

    protected ArrayCapabilityChecker $capabilities;

    protected Gate $gate;

    protected function setUp(): void
    {
        parent::setUp();

        $app = Bootstrap::run(
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
                FieldsServiceProvider::class,
                ApiServiceProvider::class,
            ]
        );

        $this->api = $app->get(RestKernel::class);
        /** @var TokenAuthenticator $auth */
        $auth = $app->get(AuthenticatorInterface::class);
        $this->auth = $auth;
        $this->auth->issue('test-token', ['id' => 1, 'name' => 'tester']);

        /** @var ArrayCapabilityChecker $caps */
        $caps = $app->get(CapabilityCheckerInterface::class);
        $this->capabilities = $caps;
        $this->gate = $app->get(Gate::class);
    }

    protected function grant(string ...$capabilities): void
    {
        $this->capabilities->grant($capabilities);
        $this->gate->flushCache();
    }
}
