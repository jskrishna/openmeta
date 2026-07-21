<?php

declare(strict_types=1);

namespace OpenMeta\Support\Reflection;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Narrow reflection helpers. Does not auto-wire DI or evaluate user code.
 */
final class Reflector
{
    /** @var array<class-string, ReflectionClass<object>> */
    private static array $classCache = [];

    /**
     * @param object|class-string $target
     * @return ReflectionClass<object>
     *
     * @throws ReflectionException
     */
    public static function classOf(object|string $target): ReflectionClass
    {
        $name = is_object($target) ? $target::class : $target;

        if (! isset(self::$classCache[$name])) {
            self::$classCache[$name] = new ReflectionClass($name);
        }

        return self::$classCache[$name];
    }

    /**
     * @param object|class-string $target
     */
    public static function shortName(object|string $target): string
    {
        return self::classOf($target)->getShortName();
    }

    /**
     * @param object|class-string $target
     *
     * @throws ReflectionException
     */
    public static function method(object|string $target, string $method): ReflectionMethod
    {
        return self::classOf($target)->getMethod($method);
    }

    /**
     * @param object|class-string $target
     *
     * @throws ReflectionException
     */
    public static function property(object|string $target, string $property): ReflectionProperty
    {
        return self::classOf($target)->getProperty($property);
    }

    /**
     * @param object|class-string $target
     * @return list<class-string>
     */
    public static function classUsesRecursive(object|string $target): array
    {
        $class = is_object($target) ? $target::class : $target;
        $traits = [];

        do {
            $traits = array_merge(class_uses($class) ?: [], $traits);
            $class = get_parent_class($class) ?: false;
        } while ($class);

        $traits = array_unique($traits);

        foreach ($traits as $trait) {
            $traits = array_merge($traits, self::classUsesRecursive($trait));
        }

        /** @var list<class-string> */
        return array_values(array_unique($traits));
    }

    /** @internal testing */
    public static function clearCache(): void
    {
        self::$classCache = [];
    }
}
