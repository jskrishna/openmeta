# Phase 15 — Code Generator

> Scaffold packages, fields, extensions, and boilerplate from OpenMeta conventions — Laravel/Symfony Maker–class DX.

Contract: [release-milestones.md](./release-milestones.md) · Decision: [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md)

**Target version:** `v0.14.0-beta`

---

## Vision

Developers run CLI commands (extends Phase 13) to generate **correct-by-convention** artifacts: extension manifests, service providers, field types, admin screens, GraphQL type stubs, and test skeletons — aligned with SPEC, dependency rules, and package layout.

---

## Objectives

- **Generator registry** — pluggable generators with templates (stub files)
- **Core generators** — extension module, field type, admin screen, REST resource stub
- **Convention enforcement** — namespaces, `openmeta_*` prefixes, PSR-12, strict types
- **Dry-run / diff preview** before write
- **Integration with Extension SDK** (Phase 14) — generated extensions boot in CI sample
- **No runtime business logic** — codegen is dev-time only

---

## Likely surfaces

| Surface | Role |
| ------- | ---- |
| CLI commands (`make:extension`, `make:field`, …) | User entry (Phase 13 spine) |
| `packages/codegen` or `cli` sub-spine | Generator engine + templates |
| Stub templates | PSR-4 paths, SPEC snippets |

SPEC required before implementation.

---

## Must not

- ❌ Generate code that inverts dependency rules
- ❌ Bypass Security / Validation in generated mutation handlers
- ❌ Replace human-written SPEC / ADR (generators scaffold, not architect)

---

## Exit criteria

- [ ] At least three generators (extension, field type, test skeleton) documented in SPEC
- [ ] Generated sample extension passes `composer ci` in CI fixture
- [ ] Dry-run mode tested
- [ ] Phase 16 gate green on new/changed packages
- [ ] `composer ci` green

---

## Prerequisites

- Phase 13 CLI spine (`openmeta` command registration)
- Phase 14 Extension SDK manifest schema stable enough to scaffold
