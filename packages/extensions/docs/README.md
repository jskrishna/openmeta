# OpenMeta Extension SDK — developer guide

The SDK lets you extend OpenMeta without touching core. This guide shows the
end-to-end flow; see [../SPEC.md](../SPEC.md) for the full contract.

## 1. Describe your extension

Ship an `openmeta.extension.json` at your package root:

```json
{
  "packageId": "acme/seo",
  "name": "SEO",
  "vendor": "acme",
  "version": "1.0.0",
  "license": "GPL-2.0-or-later",
  "minimumCoreVersion": "0.10.0",
  "providers": ["Acme\\Seo\\SeoServiceProvider"],
  "dependencies": { "acme/core-kit": "^1.0" },
  "requirements": { "php": ">=8.3" }
}
```

## 2. Contribute resources from a provider

```php
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Extensions\Contracts\ResourceLoaderInterface;

final class SeoServiceProvider extends ServiceProvider
{
    public function boot(ContainerInterface $container): void
    {
        /** @var ResourceLoaderInterface $resources */
        $resources = $container->get(ResourceLoaderInterface::class);
        $resources->field('seo_title', SeoTitleField::class, 'acme/seo');
    }
}
```

The SDK **aggregates** these registrations; each host package (Fields, Admin,
REST, …) drains the resource types it owns. The SDK never mounts into host
internals — that would invert the dependency graph (ADR-0008).

## 3. Boot at runtime

```php
use OpenMeta\Extensions\Compatibility\Environment;
use OpenMeta\Extensions\Contracts\ExtensionManagerInterface;

/** @var ExtensionManagerInterface $manager */
$manager = $container->get(ExtensionManagerInterface::class);

$report = $manager->bootstrap(Environment::detect(coreVersion: '0.13.0'));
// $report->activated  → package ids activated, in dependency order
// $report->skipped    → packageId => reasons (incompatible / unresolved)
```

`bootstrap()` discovers manifests, filters by compatibility, orders by
dependency, then installs and activates — one broken extension never takes
down the boot.

## 4. Manage lifecycle explicitly

```php
$manager->install($manifest);      // → Installed  (+ ExtensionInstalled)
$manager->activate('acme/seo');    // → Active     (loads providers, + ExtensionActivated)
$manager->deactivate('acme/seo');  // → Installed  (+ ExtensionDeactivated)
$manager->disable('acme/seo');     // → Disabled   (+ ExtensionDisabled)
$manager->update($newManifest);    // version replaced (+ ExtensionUpdated)
$manager->uninstall('acme/seo');   // removed      (+ ExtensionRemoved)
```

Listen for events on the Core dispatcher:

```php
$events->listen(ExtensionActivated::class, fn ($e) => log("up: {$e->packageId}"));
```

## Version constraints

`VersionComparator` understands `1.2.3`, `^1.2`, `~1.2.3`, `>=1.0 <2.0`,
`1.0.0 || 2.0.0`, and `*`.

## Not included

Marketplace, cloud sync, auto-updates, licensing, and payments are out of
scope — they belong to future packages.
