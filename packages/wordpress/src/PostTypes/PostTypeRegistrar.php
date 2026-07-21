<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\PostTypes;

use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Registers custom post types from array definitions.
 */
final class PostTypeRegistrar
{
    public function __construct(private readonly WordPressRuntimeInterface $runtime)
    {
    }

    /**
     * @param array<string, array<string, mixed>> $definitions
     */
    public function register(array $definitions): void
    {
        foreach ($definitions as $postType => $args) {
            if (! is_string($postType) || $postType === '') {
                continue;
            }

            $this->runtime->registerPostType($postType, is_array($args) ? $args : []);
        }
    }
}
