# Release Plan

Versioned path from Core Bootstrap to stable **v1.0.0**.

**Phase 18** is the authoritative release freeze. Related: [ROADMAP.md](../../ROADMAP.md), [phase-18-releases.md](./phase-18-releases.md), [ADR-0024](../adr/ADR-0024-post-rest-phase-order.md), [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md), [release-process.md](../development/release-process.md), [`.github/MILESTONES.md`](../../.github/MILESTONES.md).

Post-REST phase order (Accepted): WordPress Adapter → Admin → Builder → GraphQL → CLI → Extension SDK → Code Generator → Testing & Performance → Developer Doc Generator → v1.0.

---

## Release train

```text
v0.1.0-alpha
Core ✅

↓

v0.2.0-alpha
Support ✅

↓

v0.3.0-alpha
Validation ✅

↓

v0.4.0-alpha
Security ✅

↓

v0.5.0-alpha
Database ✅

↓

v0.6.0-alpha
Field Engine ✅

↓

v0.7.0-alpha
REST (framework HTTP) ✅

↓

v0.8.0-alpha
WordPress Adapter ✅

↓

v0.9.0-alpha
Admin UI ✅

↓

v0.10.0-beta
Visual Builder ✅

↓

v0.11.0-beta
GraphQL Package  ← next

↓

v0.12.0-beta
CLI & Developer Tools

↓

v0.13.0-beta
Extension SDK

↓

v0.14.0-beta
Code Generator

↓

v0.15.0-beta
Developer Documentation Generator

↓

v1.0.0
Stable
```

Phase 16 (Testing, QA & Performance) is a **continuous gate** on every train and a dedicated hardening pass before v1.0 — see [packages/TESTING.md](../../packages/TESTING.md).

---

## Version → packages

| Version | Theme | Primary package | Also required before exit |
| ------- | ----- | --------------- | ------------------------- |
| **v0.1.0-alpha** | Core | `core` | — |
| **v0.2.0-alpha** | Support | `support` | — |
| **v0.3.0-alpha** | Validation | `validation` | — |
| **v0.4.0-alpha** | Security | `security` | — |
| **v0.5.0-alpha** | Database | `database` | Connection → Relations |
| **v0.6.0-alpha** | Field Engine | `fields` | Built-in types + validate→save→load |
| **v0.7.0-alpha** | REST | `rest` (+ `api` app surface) | Router/kernel; no WP mount required |
| **v0.8.0-alpha** | WordPress Adapter | `wordpress` | Plugin boot, hooks, REST mount, caps |
| **v0.9.0-alpha** | Admin UI | `admin` | `ui` kit + screens via WP bridges |
| **v0.10.0-beta** | Visual Builder | `builder` | Schema/history pipeline + admin slot |
| **v0.11.0-beta** | GraphQL | `graphql` / `api` GraphQL mount | Schema maps from Fields contracts |
| **v0.12.0-beta** | CLI & Developer Tools | `cli` | Commands over Core container |
| **v0.13.0-beta** | Extension SDK | `sdk` (or Core extension spine) | Manifest, loader, sample extension |
| **v0.14.0-beta** | Code Generator | codegen / CLI `make:*` | Scaffold extension, field, tests |
| **v0.15.0-beta** | Developer Documentation Generator | docgen / CLI `docs:*` | Generated reference + hooks catalog |
| **v1.0.0** | Stable | — | Phases 14–17 green; Phase 16 gate; API freeze |

Status today: **through Visual Builder (`v0.10`) ✅** · next **`v0.11` GraphQL Package**.

---

## Exit criteria (every train)

1. Package SPEC(s) for that train implemented (Must not respected).
2. Testing gate: Unit → Integration → WordPress → Performance → Security ([TESTING.md](../../packages/TESTING.md)).
3. `composer ci` green.
4. CHANGELOG entry for the version.
5. GitHub milestone closed for the train.

---

## Maturity stages

| Stage | Versions | Intent |
| ----- | -------- | ------ |
| **Alpha** | `v0.1`–`v0.9` | Spines; APIs may shift |
| **Beta** | `v0.10`–`v0.15` | Builder, GraphQL, CLI, SDK, codegen, docgen soak |
| **Stable** | `v1.0.0` | SemVer-stable public contracts |

Do not skip trains. Do not claim a version “done” without its testing gates (or explicit N/A in SPEC).
