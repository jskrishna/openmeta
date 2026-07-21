# Phase 17 — Developer Documentation Generator

> Keep Architecture First / Documentation First sustainable — generated reference from SPEC, PHPDoc, and manifests.

Contract: [release-milestones.md](./release-milestones.md) · Decision: [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md)

**Target version:** `v0.15.0-beta`

> **Note:** Was “Phase 16” under [ADR-0025](../adr/ADR-0025-extended-roadmap-to-v1.md). **Phase 17** per [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md).

---

## Vision

Contributors and extension authors get **always-current** API reference, hook catalogs, and package guides — generated from the same sources of truth the framework already uses (SPEC, PHPDoc, extension manifests, ADR index).

Comparable DX goal: Laravel/Symfony docs that track the codebase, not a stale wiki.

---

## Objectives

- **CLI commands** (extends Phase 13) — e.g. `openmeta docs:generate`, `openmeta docs:hooks`, `openmeta docs:package`
- **Inputs** — package `SPEC.md`, public PHPDoc, `composer.json` manifests, extension schema from Phase 15
- **Outputs** — markdown (or static site) under `docs/generated/` or publish pipeline; hook/filter catalog
- **Cross-links** — ADR index, roadmap phase, package README stubs
- **CI hook** — fail or warn when generated docs drift from sources (optional strict mode pre-v1.0)

---

## Must not

- ❌ Replacing human-written architecture docs (SPEC, ADR, guides)
- ❌ Documenting `@internal` or private implementation as stable API
- ❌ Generator depending on WordPress runtime

---

## Exit criteria

- [ ] Generator runs headless in CI without WP
- [ ] At minimum: public class/method index per ship package + hooks catalog
- [ ] SPEC for docgen tool/package complete
- [ ] Sample extension from Phase 15 appears in generated extension guide
- [ ] Phase 16 gate green
- [ ] `composer ci` green

---

## Prerequisites

- Phase 14 SDK manifest schema stable enough to document
- Phase 15 codegen stub catalog indexed where applicable
- Phase 13 CLI spine for command registration
