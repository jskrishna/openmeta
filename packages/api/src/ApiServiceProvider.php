<?php

declare(strict_types=1);

namespace OpenMeta\Api;

use OpenMeta\Api\Auth\AuthenticatorInterface;
use OpenMeta\Api\Auth\TokenAuthenticator;
use OpenMeta\Api\Auth\WordPressAuthenticator;
use OpenMeta\Api\Authz\Authorizer;
use OpenMeta\Api\Rest\Controllers\FieldValueController;
use OpenMeta\Api\Rest\RestKernel;
use OpenMeta\Api\Rest\Routes\RouteRegistrar;
use OpenMeta\Api\Rest\Routes\Router;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Fields\FieldEngine;
use OpenMeta\Security\Permissions\Gate;

/**
 * Binds REST stack and registers default routes on boot.
 */
final class ApiServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(Router::class, static fn (): Router => new Router());

        $container->singleton(AuthenticatorInterface::class, static function (): AuthenticatorInterface {
            if (function_exists('is_user_logged_in')) {
                return new WordPressAuthenticator();
            }

            return new TokenAuthenticator();
        });

        $container->singleton(Authorizer::class, static function (ContainerInterface $c): Authorizer {
            return new Authorizer($c->get(Gate::class));
        });

        $container->singleton(
            FieldValueController::class,
            static function (ContainerInterface $c): FieldValueController {
                return new FieldValueController($c->get(FieldEngine::class));
            }
        );

        $container->singleton(RestKernel::class, static function (ContainerInterface $c): RestKernel {
            return new RestKernel(
                $c->get(Router::class),
                $c->get(AuthenticatorInterface::class),
                $c->get(Authorizer::class),
                $c,
            );
        });

        $container->alias(RestKernel::class, 'api');
        $container->alias(Router::class, 'api.router');
    }

    public function boot(ContainerInterface $container): void
    {
        /** @var Router $router */
        $router = $container->get(Router::class);
        (new RouteRegistrar())->register($router);
    }
}
