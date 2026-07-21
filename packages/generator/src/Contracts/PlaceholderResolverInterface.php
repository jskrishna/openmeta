<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Contracts;

use OpenMeta\Generator\Configuration\GeneratorConfiguration;

/**
 * Derives the full set of template variables (class, name, snake, kebab,
 * plural, author, license, year, …) from a raw name plus extras.
 */
interface PlaceholderResolverInterface
{
    /**
     * @param array<string, mixed> $extra
     *
     * @return array<string, string>
     */
    public function resolve(string $name, array $extra, GeneratorConfiguration $config): array;
}
