# SPEC — `@openmeta/sdk`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Phase 14 · `v0.13.0-beta` (Extension SDK — [ADR-0026](../../docs/adr/ADR-0026-complete-framework-ecosystem.md))

---

## Purpose

Provide a formal, stable extension architecture so third-party developers can add fields, services, UI, APIs, storage adapters, and integrations **without modifying OpenMeta core** ([ADR-0008](../../docs/adr/ADR-0008-extension-system.md)). Extensions are distributed as isolated plugins that participate through documented contracts ([ADR-0009](../../docs/adr/ADR-0009-plugin-system.md)), versioned with SemVer ([ADR-0019](../../docs/adr/ADR-0019-versioning.md)).

The SDK is the **outer ecosystem layer**: it may depend on framework packages; **no package may depend on it**.

## Component map

```text
Manifest → Discovery → Registry → Versioning → Compatibility
  → Dependency Resolution → Lifecycle → Providers (loader) → Resources → Events → Manager (façade)
```

## Manifest
### Responsibilities
Immutable metadata: name, vendor, packageId, version, description, author, license, dependencies, min/max OpenMeta version, service providers, assets, commands, configuration, permissions, feature flags, environment requirements. Parse + validate from array or JSON.
### Public contracts
`ManifestInterface`, `Manifest`, `ManifestFactory`, `Dependency`, `Requirements`.
### Must not
Execute extension code; resolve services; touch the filesystem (that is Discovery's job).

## Discovery
### Responsibilities
Produce manifests from: manual registration, local directories, and Composer (`installed.json`, type `openmeta-extension` or `extra.openmeta`). Aggregate + de-duplicate by package id.
### Public contracts
`DiscoveryInterface`, `ManualDiscovery`, `DirectoryDiscovery`, `ComposerDiscovery`, `DiscoveryManager`.
### Must not
Install or activate anything; discovery is read-only.

## Registry
### Responsibilities
Hold `Extension` entities (manifest + lifecycle state) keyed by package id; query by id and by state.
### Public contracts
`ExtensionRegistryInterface`, `ExtensionRegistry`, `Extension`, `ExtensionInterface`.

## Versioning
### Responsibilities
SemVer parsing, comparison, and constraint satisfaction (`^`, `~`, ranges, `||`, `*`). Track installed versions and answer "is there an update?".
### Public contracts
`VersionComparatorInterface`, `VersionComparator`, `Version`, `VersionConstraint`, `VersionManager`.

## Compatibility
### Responsibilities
Validate a manifest against an `Environment`: core version window, PHP version, WordPress version, required PHP extensions, and required extension dependencies (presence + version).
### Public contracts
`CompatibilityCheckerInterface`, `CompatibilityChecker`, `Environment`, `CompatibilityReport`.

## Dependency Resolution
### Responsibilities
Topologically order manifests (dependencies first); detect missing **required** dependencies and circular chains. Ignore absent **optional** dependencies.
### Public contracts
`DependencyResolverInterface`, `DependencyResolver`.

## Lifecycle
### Responsibilities
State machine over `Installed · Active · Disabled`: install, activate (loads providers), deactivate, disable, update, uninstall — each emitting the matching event.
### Public contracts
`LifecycleManagerInterface`, `LifecycleManager`, `ExtensionState`.
### Must not
Perform compatibility or dependency gating (that happens upstream) — single reason to change.

## Providers
### Responsibilities
Instantiate, `register()`, and `boot()` an extension's service providers into the running container (extensions activate after the kernel is Ready). Skip already-loaded providers.
### Public contracts
`ServiceProviderLoaderInterface`, `ServiceProviderLoader`.

## Resources
### Responsibilities
Aggregation surface for extension-contributed resources — fields, components, pages, routes, middleware, templates, assets, migrations, menus, widgets. Hosts drain the relevant type through their own adapters.
### Public contracts
`ResourceLoaderInterface`, `ResourceLoader`, `ResourceType`, `ResourceRegistration`.
### Must not
Mount resources into host internals itself (would invert the dependency graph and violate ADR-0008).

## Events
Reuse the Core `EventDispatcher`. Emit `ExtensionInstalled`, `ExtensionActivated`, `ExtensionDeactivated`, `ExtensionUpdated`, `ExtensionDisabled`, `ExtensionRemoved`.

## Manager
### Responsibilities
Public façade composing discovery, registry, lifecycle, resources, feature flags, and the bootstrapper. `bootstrap(Environment)` runs discover → filter compatible → resolve order → install + activate, returning a `BootstrapReport`; one broken extension never fails the boot.
### Public contracts
`ExtensionManagerInterface`, `ExtensionManager`, `ExtensionBootstrapper`, `BootstrapReport`, `FeatureFlagsInterface`, `FeatureFlags`.

## Public Contracts (package index)

`ExtensionManagerInterface` · `ExtensionRegistryInterface` · `ManifestInterface` / `ManifestFactory` · `DiscoveryInterface` · `LifecycleManagerInterface` · `CompatibilityCheckerInterface` · `DependencyResolverInterface` · `VersionComparatorInterface` · `ResourceLoaderInterface` · `FeatureFlagsInterface` · `ServiceProviderLoaderInterface`.

## Internal Components

Discovery sources, the version engine internals, the topological resolver, and the provider loader are implementation details reached only through the interfaces above.

## Folder Structure

```text
packages/sdk/src/
  Bootstrap/      ExtensionBootstrapper, BootstrapReport
  Compatibility/  CompatibilityChecker, Environment, CompatibilityReport
  Contracts/      all package interfaces
  Discovery/      Manual/Directory/Composer/DiscoveryManager
  Events/         Extension{Installed,Activated,Deactivated,Updated,Disabled,Removed}
  Exceptions/     SdkException + typed subclasses
  Lifecycle/      LifecycleManager, ExtensionState
  Manager/        ExtensionManager (façade)
  Manifest/       Manifest, ManifestFactory, Dependency, Requirements
  Providers/      ServiceProviderLoader
  Registry/       ExtensionRegistry, Extension
  Resources/      ResourceLoader, ResourceType, ResourceRegistration
  Support/        DependencyResolver, FeatureFlags
  Versioning/     Version, VersionConstraint, VersionComparator, VersionManager
  SdkServiceProvider.php
```

## Dependency Rules

May depend on: Core, Support, Validation, Security, Database, Fields, REST (+ WordPress/Admin/Builder as an outermost layer). **No package may depend on the SDK.** In practice this implementation binds only to **Core** contracts and **Support** (`FilesystemInterface`), keeping the coupling minimal.

## Lifecycle

```text
install → Installed
activate ↔ deactivate   (Installed ↔ Active; activate loads providers)
disable → Disabled       (Installed | Active → Disabled; re-activate to enable)
update → (version replaced, state preserved)
uninstall → removed
```

## Extension Points

Third parties ship a manifest + one or more `ServiceProviderInterface` classes; inside those providers they use `ResourceLoaderInterface` to contribute resources and `FeatureFlagsInterface` to gate behavior.

## Performance

Discovery and resolution are O(n) over the extension set; the resolver is a single DFS with memoization. No I/O in the hot path — discovery reads once, results are held in memory.

## Security

Extensions declare `permissions` and `requirements`; the host enforces capabilities via `@openmeta/security`. The SDK loads only providers listed in a manifest and validates `class-string` + `ServiceProviderInterface` before instantiation.

## Testing Strategy

> Phase 16 gate:

| Layer | What to cover |
| ----- | ------------- |
| Unit | Version/constraint math, manifest parsing, registry, resolver, compatibility, discovery, resources, feature flags, lifecycle transitions + events |
| Integration | Boot the framework with `SdkServiceProvider`, seed discovery, and bootstrap extensions in dependency order (`tests/Integration`) |
| WordPress Compatibility | **N/A** — the SDK is host-agnostic; WordPress version checking is validated via `Environment` unit tests, and any WP mount lives in `@openmeta/wordpress` |
| Performance | Resolution/compat are linear over the extension set; no dedicated budget test at this size |

Full matrix: [TESTING.md](../TESTING.md).

## Future Scope

Explicitly **not** built here: marketplace, cloud sync, auto-updates, licensing, payments. Deferred to future packages.
