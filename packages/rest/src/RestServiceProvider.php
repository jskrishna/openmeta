<?php

declare(strict_types=1);

namespace OpenMeta\Rest;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Rest\Authentication\NullAuthenticator;
use OpenMeta\Rest\Authorization\GateAuthorizer;
use OpenMeta\Rest\Contracts\AuthenticatorInterface;
use OpenMeta\Rest\Contracts\RouterInterface;
use OpenMeta\Rest\Exceptions\ExceptionHandler;
use OpenMeta\Rest\Middleware\Pipeline;
use OpenMeta\Rest\Router\RouteRegistry;
use OpenMeta\Rest\Router\Router;
use OpenMeta\Rest\Support\RequestValidator;
use OpenMeta\Rest\Transformers\TransformerRegistry;
use OpenMeta\Security\Permissions\Gate;

/**
 * Registers the framework REST stack.
 */
final class RestServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(Router::class, static fn (): Router => new Router());
        $container->singleton(
            RouterInterface::class,
            static fn (ContainerInterface $c): RouterInterface => $c->get(Router::class)
        );

        $container->singleton(
            RouteRegistry::class,
            static fn (ContainerInterface $c): RouteRegistry => new RouteRegistry($c->get(Router::class))
        );

        $container->singleton(
            AuthenticatorInterface::class,
            static fn (): AuthenticatorInterface => new NullAuthenticator()
        );

        $container->singleton(
            GateAuthorizer::class,
            static fn (ContainerInterface $c): GateAuthorizer => new GateAuthorizer($c->get(Gate::class))
        );

        $container->singleton(
            Pipeline::class,
            static fn (ContainerInterface $c): Pipeline => new Pipeline($c)
        );

        $container->singleton(
            ExceptionHandler::class,
            static fn (): ExceptionHandler => new ExceptionHandler()
        );

        $container->singleton(
            RequestValidator::class,
            static fn (): RequestValidator => new RequestValidator()
        );

        $container->singleton(
            TransformerRegistry::class,
            static fn (): TransformerRegistry => new TransformerRegistry()
        );

        $container->singleton(RestKernel::class, static function (ContainerInterface $c): RestKernel {
            return new RestKernel(
                $c->get(RouterInterface::class),
                $c->get(Pipeline::class),
                $c->get(AuthenticatorInterface::class),
                $c->get(GateAuthorizer::class),
                $c->get(ExceptionHandler::class),
                $c->get(EventDispatcherInterface::class),
                $c,
                [],
            );
        });

        $container->alias(Router::class, 'rest.router');
        $container->alias(RestKernel::class, 'rest');
    }

    public function boot(ContainerInterface $container): void
    {
    }
}
