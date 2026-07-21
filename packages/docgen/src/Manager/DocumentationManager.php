<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Manager;

use OpenMeta\Docgen\Api\ApiDocGenerator;
use OpenMeta\Docgen\Changelog\ChangelogGenerator;
use OpenMeta\Docgen\Configuration\DocgenConfiguration;
use OpenMeta\Docgen\Contracts\DocValidatorInterface;
use OpenMeta\Docgen\Model\DocPage;
use OpenMeta\Docgen\Model\ValidationReport;
use OpenMeta\Docgen\Packages\PackageDocGenerator;
use OpenMeta\Docgen\Search\SearchIndexGenerator;
use OpenMeta\Docgen\Sitemap\SitemapGenerator;
use OpenMeta\Docgen\Support\DocDiscovery;
use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Public façade for the documentation platform: build API/package docs, the
 * search index, the sitemap, the changelog, and validate the docs tree.
 */
final class DocumentationManager
{
    public function __construct(
        private readonly DocgenConfiguration $config,
        private readonly FilesystemInterface $filesystem,
        private readonly DocDiscovery $discovery,
        private readonly DocValidatorInterface $validator,
        private readonly ApiDocGenerator $api,
        private readonly PackageDocGenerator $packages,
        private readonly SearchIndexGenerator $search,
        private readonly SitemapGenerator $sitemap,
        private readonly ChangelogGenerator $changelog,
    ) {
    }

    /**
     * @return list<DocPage>
     */
    public function pages(): array
    {
        return $this->discovery->discover($this->config->path($this->config->docsPath));
    }

    public function validate(): ValidationReport
    {
        return $this->validator->validate($this->pages());
    }

    /**
     * @return list<string>
     */
    public function generateApi(): array
    {
        return $this->api->generate($this->config);
    }

    public function generatePackages(): string
    {
        return $this->packages->generate($this->config);
    }

    public function generateSearchIndex(): string
    {
        $path = $this->config->path($this->config->assetsPath) . '/search-index.json';
        $this->filesystem->put($path, $this->search->build($this->pages()));

        return $path;
    }

    public function generateSitemap(): string
    {
        $path = $this->config->path($this->config->assetsPath) . '/sitemap.xml';
        $this->filesystem->put($path, $this->sitemap->build($this->pages(), $this->config->baseUrl));

        return $path;
    }

    public function generateChangelog(): string
    {
        $source = $this->config->path('CHANGELOG.md');
        $changelog = $this->filesystem->isFile($source) ? $this->filesystem->get($source) : '# Changelog';

        $path = $this->config->path($this->config->docsPath) . '/reference/changelog.md';
        $this->filesystem->put($path, $this->changelog->build($changelog));

        return $path;
    }

    /**
     * Run the full pipeline; returns every written path.
     *
     * @return list<string>
     */
    public function build(): array
    {
        return [
            ...$this->generateApi(),
            $this->generatePackages(),
            $this->generateSearchIndex(),
            $this->generateSitemap(),
            $this->generateChangelog(),
        ];
    }
}
