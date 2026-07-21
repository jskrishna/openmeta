<?php

declare(strict_types=1);

namespace OpenMeta\Docgen;

use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Cli\Discovery\CommandDiscovery;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Docgen\Api\ApiDocGenerator;
use OpenMeta\Docgen\Api\ApiDocRenderer;
use OpenMeta\Docgen\Api\ApiScanner;
use OpenMeta\Docgen\Changelog\ChangelogGenerator;
use OpenMeta\Docgen\Cli\DocsCommandProvider;
use OpenMeta\Docgen\Configuration\DocgenConfiguration;
use OpenMeta\Docgen\Contracts\DocValidatorInterface;
use OpenMeta\Docgen\Manager\DocumentationManager;
use OpenMeta\Docgen\Packages\PackageDocGenerator;
use OpenMeta\Docgen\Search\SearchIndexGenerator;
use OpenMeta\Docgen\Sitemap\SitemapGenerator;
use OpenMeta\Docgen\Support\DocDiscovery;
use OpenMeta\Docgen\Validation\DocValidator;
use OpenMeta\Docgen\Validation\LinkValidator;
use OpenMeta\Docgen\Validation\MarkdownLinter;
use OpenMeta\Support\Filesystem\LocalFilesystem;

/**
 * Registers the documentation platform and mounts its `docs:*` commands into
 * the CLI.
 */
final class DocgenServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $filesystem = new LocalFilesystem();

        $container->singleton(
            DocgenConfiguration::class,
            static fn (): DocgenConfiguration => new DocgenConfiguration(),
        );
        $container->singleton(ApiScanner::class, static fn (): ApiScanner => new ApiScanner());
        $container->singleton(ApiDocRenderer::class, static fn (): ApiDocRenderer => new ApiDocRenderer());
        $container->singleton(DocDiscovery::class, static fn (): DocDiscovery => new DocDiscovery());
        $container->singleton(MarkdownLinter::class, static fn (): MarkdownLinter => new MarkdownLinter());
        $container->singleton(
            SearchIndexGenerator::class,
            static fn (): SearchIndexGenerator => new SearchIndexGenerator(),
        );
        $container->singleton(SitemapGenerator::class, static fn (): SitemapGenerator => new SitemapGenerator());
        $container->singleton(ChangelogGenerator::class, static fn (): ChangelogGenerator => new ChangelogGenerator());

        $container->singleton(
            LinkValidator::class,
            static fn (): LinkValidator => new LinkValidator(new LocalFilesystem()),
        );

        $container->singleton(DocValidator::class, static function (ContainerInterface $c): DocValidator {
            return new DocValidator($c->get(LinkValidator::class), $c->get(MarkdownLinter::class));
        });
        $container->alias(DocValidator::class, DocValidatorInterface::class);

        $container->singleton(ApiDocGenerator::class, static function (ContainerInterface $c) use ($filesystem) {
            return new ApiDocGenerator($c->get(ApiScanner::class), $c->get(ApiDocRenderer::class), $filesystem);
        });

        $container->singleton(
            PackageDocGenerator::class,
            static fn (): PackageDocGenerator => new PackageDocGenerator(new LocalFilesystem()),
        );

        $container->singleton(DocumentationManager::class, static function (ContainerInterface $c) use ($filesystem) {
            return new DocumentationManager(
                $c->get(DocgenConfiguration::class),
                $filesystem,
                $c->get(DocDiscovery::class),
                $c->get(DocValidatorInterface::class),
                $c->get(ApiDocGenerator::class),
                $c->get(PackageDocGenerator::class),
                $c->get(SearchIndexGenerator::class),
                $c->get(SitemapGenerator::class),
                $c->get(ChangelogGenerator::class),
            );
        });
        $container->alias(DocumentationManager::class, 'docs');
    }

    public function boot(ContainerInterface $container): void
    {
        if (! $container->has(CommandRegistryInterface::class)) {
            return;
        }

        $discovery = new CommandDiscovery($container->get(CommandRegistryInterface::class));
        $discovery->fromProviders([
            new DocsCommandProvider($container->get(DocumentationManager::class)),
        ]);
    }
}
