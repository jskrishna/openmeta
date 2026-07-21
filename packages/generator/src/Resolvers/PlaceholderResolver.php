<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Resolvers;

use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Contracts\PlaceholderResolverInterface;
use OpenMeta\Support\Str\Str;

/**
 * Derives the standard template variables from a raw name and merges in extras
 * (extras win, so a generator can override `namespace`, `extends`, etc.).
 */
final class PlaceholderResolver implements PlaceholderResolverInterface
{
    public function resolve(string $name, array $extra, GeneratorConfiguration $config): array
    {
        $class = Str::studly($name);
        $snake = Str::snake($class);
        $kebab = str_replace('_', '-', $snake);

        $variables = [
            'name' => $name,
            'class' => $class,
            'snake' => $snake,
            'kebab' => $kebab,
            'plural' => $this->plural($snake),
            'author' => $config->author,
            'license' => $config->license,
            'year' => $config->year !== '' ? $config->year : date('Y'),
        ];

        foreach ($extra as $key => $value) {
            $variables[$key] = $this->stringify($value);
        }

        return $variables;
    }

    private function plural(string $value): string
    {
        return str_ends_with($value, 's') ? $value : $value . 's';
    }

    private function stringify(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? '1' : '';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }
}
