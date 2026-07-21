# Phase 14 — Extension SDK

> First-class module authoring for third-party extensions — not ad-hoc WordPress hooks alone.

Contract: [release-milestones.md](./release-milestones.md) · Decision: [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md) · Existing sketch: [docs/api/sdk.md](../api/sdk.md)

**Target version:** `v0.13.0-beta`

> **Note:** Was “Phase 15” under [ADR-0025](../adr/ADR-0025-extended-roadmap-to-v1.md). **Phase 14** per [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md).

---

## Vision

OpenMeta becomes extensible like Laravel packages or Symfony bundles: authors ship a **module** with a semver contract, service provider, manifest, and documented extension points — mounted through the WordPress adapter when needed, testable without WP in CI.

---

## Objectives

- **Extension manifest** — name, version, dependencies, providers, hooks declared in a machine-readable format
- **Module loader** — discover, validate, and boot extensions through Core container + providers
- **Public SDK façade** — stable helpers for Fields, Admin screens, REST/GraphQL registration (no internals)
- **Hook & filter registry** — documented `openmeta_*` extension points with context objects
- **Compatibility policy** — semver ranges, deprecation warnings, migration hooks
- **WordPress bridge** — optional plugin header / `plugins_loaded` integration without inverting dependencies

---

## Likely packages / surfaces

| Surface | Role |
| ------- | ---- |
| `packages/extensions` (Extension SDK, namespace `OpenMeta\Extensions`) | Manifest, discovery, lifecycle, resources, author-facing API |
| `packages/wordpress` | WP plugin discovery + activation lifecycle |
| Existing providers | Extensions register via `ServiceProvider` pattern |

SPEC required before implementation — do not invent package boundaries in code first.

---

## Must not

- ❌ Extensions bypassing Security Gate / Nonce / Validation
- ❌ Global function pollution or unprefixed hooks
- ❌ Domain engines reimplemented inside extension samples
- ❌ Core depending on any specific extension

---

## Exit criteria

- [ ] Extension manifest schema documented + validated
- [ ] Sample extension boots in CI (`ArrayWordPressRuntime`) and registers a field + admin screen
- [ ] SDK public API listed in SPEC with semver stability intent
- [ ] Unit + Integration tests for loader and compatibility checks
- [ ] Phase 16 gate green on new/changed packages
- [ ] `composer ci` green

---

## Prerequisites

- Phases 09–13 complete (WP adapter, Admin, Builder, GraphQL, CLI)
- Phase 16 performance baselines available for loader boot budget
