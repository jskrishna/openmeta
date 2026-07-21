<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Configuration;

/**
 * Paths and metadata the documentation platform operates against.
 *
 * All directories are relative to {@see $rootPath} unless already absolute.
 */
final class DocgenConfiguration
{
    public function __construct(
        public readonly string $rootPath = '.',
        public readonly string $docsPath = 'docs',
        public readonly string $packagesPath = 'packages',
        public readonly string $apiPath = 'docs/reference/api',
        public readonly string $assetsPath = 'docs/assets',
        public readonly string $baseUrl = '/',
        public readonly string $version = 'v0.x',
    ) {
    }

    public function path(string $relative): string
    {
        if ($relative === '' || $relative[0] === '/' || preg_match('#^[A-Za-z]:#', $relative) === 1) {
            return $relative;
        }

        return rtrim($this->rootPath, '/\\') . '/' . $relative;
    }
}
