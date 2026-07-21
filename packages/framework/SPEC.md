# SPEC — `@openmeta/framework`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ `v0.13.0-beta` (meta package)

---

## Purpose

Provide a single, batteries-included install and bootstrap target that aggregates every stable OpenMeta package — the `laravel/framework` / Symfony-meta-package pattern. Most developers `composer require openmeta/framework` and call `Framework::boot()`; advanced users still install individual components (`openmeta/fields`, `openmeta/database`, …).

## Component map

```text
composer require (aggregate dependencies)  →  Framework::providers()  →  Framework::boot()
```

## Framework
### Responsibilities
- `providers()` — the ordered list of all aggregated service providers (Core is bootstrapped by `Bootstrap::run()` itself and is not listed).
- `boot(config, extraProviders)` — merge framework defaults over `config`, then boot the Core application with the aggregate provider set plus any extras.
- `VERSION` — the framework's beta release line.
### Must not
Contain domain/business logic, re-implement any package, or add a service provider of its own — it only aggregates.

## Public Contracts (package index)

`Framework::providers()` · `Framework::boot()` · `Framework::VERSION`.

## Folder Structure

```text
packages/framework/
  src/Framework.php
  README.md · SPEC.md
```

## Dependency Rules

Requires **every** stable OpenMeta package (the aggregation). It is the **outermost** layer — even beyond the CLI. **No package may depend on `framework`.** Its own code imports only Core (`Application`, `Bootstrap`, `ServiceProviderInterface`) plus each package's public `*ServiceProvider` class.

## Boot Order

Providers register in dependency order; the Kernel runs all `register()` before any `boot()`, so bindings from upstream packages exist before any downstream `boot()` runs:

```text
Support → Validation → Security → Database → Fields → Rest → Api
  → Ui → Admin → Builder → Wordpress → Extensions → GraphQL → Cli
```

## Testing Strategy

> Phase 16 gate:

| Layer | What to cover |
| ----- | ------------- |
| Unit | `providers()` are all real `ServiceProviderInterface`s; `VERSION` shape |
| Integration | `Framework::boot()` returns a booted application; services from every layer (security, fields, graphql, cli, extensions) resolve; extra providers append |
| WordPress Compatibility | **N/A** — bundled providers guard WP calls with `function_exists()`; boots headless |
| Performance | Boot is O(providers); no dedicated budget |

Full matrix: [TESTING.md](../TESTING.md).

## Future Scope

If/when packages publish independently, this remains the aggregate target. A pure Composer `metapackage` (no `Framework` bootstrapper) is an alternative if the convenience bootstrap is ever split out; for now the bootstrapper adds real value.
