<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Middleware;

/**
 * Middleware execution priority — lower values run first.
 */
enum MiddlewarePriority: int
{
    case High = 10;
    case Normal = 50;
    case Low = 100;

    /**
     * @param list<class-string|object> $middleware
     * @return list<class-string|object>
     */
    public static function sort(array $middleware): array
    {
        usort(
            $middleware,
            static function (object|string $a, object|string $b): int {
                return self::resolve($a) <=> self::resolve($b);
            }
        );

        return $middleware;
    }

    private static function resolve(object|string $middleware): int
    {
        if (is_object($middleware) && method_exists($middleware, 'priority')) {
            $priority = $middleware->priority();

            return $priority instanceof self ? $priority->value : (int) $priority;
        }

        if (is_string($middleware) && class_exists($middleware) && method_exists($middleware, 'priority')) {
            $priority = $middleware::priority();

            return $priority instanceof self ? $priority->value : (int) $priority;
        }

        return self::Normal->value;
    }
}
