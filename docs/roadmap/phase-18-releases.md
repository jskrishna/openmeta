# Phase 18 — Stable v1.0 Release

> Authoritative product version freeze: SemVer-stable **v1.0.0**.

Contract: [release-milestones.md](./release-milestones.md) · Process: [release-process.md](../development/release-process.md) · Decision: [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md)

> **Note:** Stable was Phase 15 (ADR-0024), Phase 17 (ADR-0025). **Phase 18** is authoritative per [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md). See [phase-17-releases.md](./phase-17-releases.md) (redirect).

---

## Train (summary)

```text
v0.1 → v0.7   Foundation (Core … REST) ✅
v0.8          WordPress Adapter ✅
v0.9          Admin UI ✅
v0.10-beta    Visual Builder ✅
v0.11-beta    GraphQL Package
v0.12-beta    CLI & Developer Tools
v0.13-beta    Extension SDK
v0.14-beta    Code Generator
v0.15-beta    Developer Documentation Generator
v1.0.0        Stable
```

---

## Objectives

- SemVer-stable public contracts: Core → Rest → Fields → WP adapter → GraphQL → CLI → SDK → codegen stubs
- Generated developer reference ships with Stable
- Migration notes (alpha → beta → stable)
- Production readiness checklist + support policy
- GitHub milestone + CHANGELOG for `v1.0.0`

---

## Prerequisites

- Phase 16 Testing, QA & Performance gate green on all ship packages
- Phase 14 SDK sample extension + compatibility policy
- Phase 15 codegen samples validated in CI
- Phase 17 doc generator output published and CI-validated
- `composer ci` green · milestone closed
