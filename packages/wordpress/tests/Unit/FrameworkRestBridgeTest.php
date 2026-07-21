<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Unit;

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Events\EventDispatcher;
use OpenMeta\Rest\Authentication\NullAuthenticator;
use OpenMeta\Rest\Authorization\GateAuthorizer;
use OpenMeta\Rest\Exceptions\ExceptionHandler;
use OpenMeta\Rest\Middleware\Pipeline;
use OpenMeta\Rest\RestKernel;
use OpenMeta\Rest\Router\Router;
use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Wordpress\Filters\FilterBridge;
use OpenMeta\Wordpress\Rest\FrameworkRestBridge;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class FrameworkRestBridgeTest extends WordpressTestCase
{
    public function test_registers_on_rest_api_init_when_routes_exist(): void
    {
        $router = new Router();
        $router->get('/health', static fn (): array => ['ok' => true], 'health');

        $container = new Container();
        $kernel = new RestKernel(
            $router,
            new Pipeline($container),
            new NullAuthenticator(),
            new GateAuthorizer(new Gate(new PermissionMap(), new ArrayCapabilityChecker())),
            new ExceptionHandler(),
            new EventDispatcher(),
            $container,
        );

        $filters = new FilterBridge($this->wp);
        $bridge = new FrameworkRestBridge($this->wp, $router, $kernel, $filters);

        $bridge->register();
        $this->assertArrayHasKey('rest_api_init', $this->wp->actions);

        $this->wp->doAction('rest_api_init');

        $this->assertNotEmpty($this->wp->restRoutes);
        $this->assertSame('openmeta/v1', $this->wp->restRoutes[0]['namespace']);
    }
}
