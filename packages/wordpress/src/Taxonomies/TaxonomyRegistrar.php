<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Taxonomies;

use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Registers taxonomies from array definitions.
 */
final class TaxonomyRegistrar
{
    public function __construct(private readonly WordPressRuntimeInterface $runtime)
    {
    }

    /**
     * @param array<string, array{object_type?: list<string>|string, args?: array<string, mixed>}> $definitions
     */
    public function register(array $definitions): void
    {
        foreach ($definitions as $taxonomy => $definition) {
            if (! is_string($taxonomy) || $taxonomy === '' || ! is_array($definition)) {
                continue;
            }

            $objectType = $definition['object_type'] ?? [];
            if (is_string($objectType)) {
                $objectType = [$objectType];
            }

            /** @var list<string> $objectTypes */
            $objectTypes = is_array($objectType) ? array_values(array_filter($objectType, 'is_string')) : [];
            $args = is_array($definition['args'] ?? null) ? $definition['args'] : [];

            $this->runtime->registerTaxonomy($taxonomy, $objectTypes, $args);
        }
    }
}
