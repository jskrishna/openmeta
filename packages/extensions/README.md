# `@openmeta/extensions`

> **Extension SDK** — the outer ecosystem layer that lets third parties build plugins, field types, storage adapters, admin modules, and API resources **without modifying OpenMeta core**.

**Status:** ✅ Phase 12 · **v0.11.0-beta**
**Blueprint:** [SPEC.md](./SPEC.md)

Core stays *closed for modification, open for extension* ([ADR-0008](../../docs/adr/ADR-0008-extension-system.md), [ADR-0009](../../docs/adr/ADR-0009-plugin-system.md)). The SDK reuses Core's container, event dispatcher, and service-provider lifecycle — it never re-implements them, and **no package depends on the SDK**.

```bash
composer test:extensions
composer ci
```

## Public API

```text
ExtensionManager (façade)
  → discover() / install() / activate() / deactivate() / disable() / update() / uninstall()
  → bootstrap(Environment)   discover → compat → resolve → activate
  → registry() / resources() / flags()

ExtensionRegistry · ManifestFactory · Discovery · LifecycleManager
```

Everything else (resolver, compatibility checker, provider loader, version engine) is an implementation detail behind an interface in [`src/Contracts`](./src/Contracts).

## Spine

```text
Manifest → Discovery → Registry → Versioning → Compatibility
  → Dependency Resolution → Lifecycle → Providers → Resources → Events → Manager
```

## Manifest (JSON)

```json
{
  "packageId": "acme/seo",
  "name": "SEO",
  "vendor": "acme",
  "version": "1.4.0",
  "license": "GPL-2.0-or-later",
  "minimumCoreVersion": "0.10.0",
  "maximumCoreVersion": "1.0.0",
  "providers": ["Acme\\Seo\\SeoServiceProvider"],
  "permissions": ["manage_seo"],
  "featureFlags": { "sitemaps": true },
  "requirements": { "php": ">=8.3", "wordpress": ">=6.4", "phpExtensions": ["json"] },
  "dependencies": { "acme/core-kit": "^1.0" }
}
```

## Out of scope

Marketplace, cloud sync, auto-updates, licensing, and payments are **explicitly deferred** to future packages.

## Docs

See [docs/README.md](./docs/README.md).
