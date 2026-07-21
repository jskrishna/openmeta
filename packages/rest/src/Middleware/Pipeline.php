<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Middleware;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Rest\Contracts\MiddlewareInterface;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\Response;

/**
 * Middleware pipeline runner with fluent builder.
 */
final class Pipeline
{
    public function __construct(private readonly ?ContainerInterface $container = null)
    {
    }

    public function send(Request $request): self
    {
        $clone = clone $this;
        $clone->request = $request;
        $clone->middleware = [];

        return $clone;
    }

    /** @var Request|null */
    private ?Request $request = null;

    /** @var list<class-string|object> */
    private array $middleware = [];

    /**
     * @param list<class-string|object> $middleware
     */
    public function through(array $middleware): self
    {
        $this->middleware = MiddlewarePriority::sort($middleware);

        return $this;
    }

    public function then(callable $destination): Response
    {
        if ($this->request === null) {
            throw new \LogicException('Call send() before then().');
        }

        $pipeline = array_reduce(
            array_reverse($this->middleware),
            function (callable $next, object|string $middleware): callable {
                return function (Request $request) use ($middleware, $next): Response {
                    $instance = $this->resolveMiddleware($middleware);

                    return $instance->handle($request, $next);
                };
            },
            $destination,
        );

        return $pipeline($this->request);
    }

    private function resolveMiddleware(object|string $middleware): MiddlewareInterface
    {
        if ($middleware instanceof MiddlewareInterface) {
            return $middleware;
        }

        if (is_string($middleware)) {
            if ($this->container !== null && $this->container->has($middleware)) {
                /** @var MiddlewareInterface $resolved */
                $resolved = $this->container->get($middleware);

                return $resolved;
            }

            /** @var MiddlewareInterface $resolved */
            $resolved = new $middleware();

            return $resolved;
        }

        throw new \InvalidArgumentException('Middleware must be an instance or class-string.');
    }
}
