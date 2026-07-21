<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Rest;

use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\Response;
use OpenMeta\Rest\RestKernel;
use OpenMeta\Rest\Router\Router;
use OpenMeta\Rest\Routes\Route;
use OpenMeta\Wordpress\Filters\FilterBridge;
use OpenMeta\Wordpress\Hooks\ActionBridge;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Mounts {@see RestKernel} routes onto WordPress REST (`rest_api_init`).
 */
final class FrameworkRestBridge
{
    public const DEFAULT_NAMESPACE = 'openmeta/v1';

    public function __construct(
        private readonly WordPressRuntimeInterface $wp,
        private readonly Router $router,
        private readonly RestKernel $kernel,
        private readonly FilterBridge $filters,
    ) {
    }

    public function register(): void
    {
        $this->wp->addAction('rest_api_init', [$this, 'onRestApiInit']);
    }

    public function onRestApiInit(): void
    {
        $this->registerRoutes();
        $this->wp->doAction(ActionBridge::REST_INIT);
    }

    private function registerRoutes(): void
    {
        $namespace = (string) $this->filters->apply(
            FilterBridge::REST_NAMESPACE,
            self::DEFAULT_NAMESPACE
        );

        foreach ($this->router->routes() as $route) {
            $path = $this->toWpPath($route->path());
            $this->wp->registerRestRoute($namespace, $path, [
                'methods' => $route->method(),
                'callback' => fn (mixed $wpRequest): array => $this->dispatch($wpRequest, $route, $namespace),
                'permission_callback' => static fn (): bool => true,
                'args' => [],
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function dispatch(mixed $wpRequest, Route $route, string $namespace): array
    {
        $method = $route->method();
        $path = '/' . trim($namespace, '/') . $route->path();
        $query = [];
        $body = [];
        $headers = [];

        if (is_object($wpRequest)) {
            if (method_exists($wpRequest, 'get_method')) {
                $method = (string) $wpRequest->get_method();
            }
            if (method_exists($wpRequest, 'get_params')) {
                /** @var array<string, mixed> $params */
                $params = (array) $wpRequest->get_params();
                $query = $params;
                $body = $params;
            }
            if (method_exists($wpRequest, 'get_headers')) {
                /** @var array<string, mixed> $raw */
                $raw = (array) $wpRequest->get_headers();
                foreach ($raw as $key => $value) {
                    $headers[(string) $key] = is_array($value)
                        ? (string) ($value[0] ?? '')
                        : (string) $value;
                }
            }
        }

        $request = new Request($method, $path, $headers, $query, $body);
        $response = $this->kernel->handle($request);

        return $this->toWpResponse($response);
    }

    /**
     * @return array<string, mixed>
     */
    private function toWpResponse(Response $response): array
    {
        $payload = $response->toArray();
        $payload['_status'] = $response->status();

        return $payload;
    }

    private function toWpPath(string $path): string
    {
        $path = trim($path, '/');
        $path = (string) preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path);

        return '/' . $path;
    }
}
