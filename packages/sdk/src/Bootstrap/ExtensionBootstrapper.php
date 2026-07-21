<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Bootstrap;

use OpenMeta\Sdk\Compatibility\Environment;
use OpenMeta\Sdk\Contracts\CompatibilityCheckerInterface;
use OpenMeta\Sdk\Contracts\DependencyResolverInterface;
use OpenMeta\Sdk\Contracts\DiscoveryInterface;
use OpenMeta\Sdk\Contracts\LifecycleManagerInterface;
use OpenMeta\Sdk\Contracts\ManifestInterface;
use OpenMeta\Sdk\Exceptions\DependencyException;
use OpenMeta\Sdk\Exceptions\LifecycleException;

/**
 * Orchestrates the boot-time flow: discover → filter compatible →
 * order by dependency → install and activate.
 *
 * Incompatible or unresolvable extensions are skipped (never fatal), so one
 * broken extension cannot take down the framework boot.
 */
final class ExtensionBootstrapper
{
    public function __construct(
        private readonly DiscoveryInterface $discovery,
        private readonly CompatibilityCheckerInterface $compatibility,
        private readonly DependencyResolverInterface $resolver,
        private readonly LifecycleManagerInterface $lifecycle,
    ) {
    }

    public function bootstrap(Environment $environment): BootstrapReport
    {
        $manifests = $this->discovery->discover();

        // Extensions in this batch will be installed together, so their
        // dependencies on one another are satisfiable — model that by
        // treating every discovered manifest as "installed" for the
        // compatibility pass. Dependency ordering and cycles are still the
        // resolver's responsibility.
        $batch = [];
        foreach ($manifests as $manifest) {
            $batch[$manifest->packageId()] = $manifest->version();
        }
        $environment = $environment->withInstalled($batch);

        /** @var list<ManifestInterface> $compatible */
        $compatible = [];
        /** @var array<string, list<string>> $skipped */
        $skipped = [];

        foreach ($manifests as $manifest) {
            $report = $this->compatibility->check($manifest, $environment);

            if ($report->compatible) {
                $compatible[] = $manifest;
            } else {
                $skipped[$manifest->packageId()] = $report->issues;
            }
        }

        try {
            $ordered = $this->resolver->resolve($compatible);
        } catch (DependencyException $exception) {
            foreach ($compatible as $manifest) {
                $skipped[$manifest->packageId()] ??= [$exception->getMessage()];
            }

            return new BootstrapReport([], $skipped);
        }

        $activated = [];

        foreach ($ordered as $manifest) {
            $id = $manifest->packageId();

            try {
                $this->lifecycle->install($manifest);
            } catch (LifecycleException) {
                // Already installed — fall through to activation.
            }

            try {
                $this->lifecycle->activate($id);
                $activated[] = $id;
            } catch (LifecycleException $exception) {
                $skipped[$id] = [$exception->getMessage()];
            }
        }

        return new BootstrapReport($activated, $skipped);
    }
}
