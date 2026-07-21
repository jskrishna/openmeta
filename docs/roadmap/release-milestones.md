# Release Plan

Versioned path from Core Bootstrap to stable **v1.0.0**.

**Phase 15** is the authoritative release freeze. Related: [ROADMAP.md](../../ROADMAP.md), [phase-15-releases.md](./phase-15-releases.md), [ADR-0024](../adr/ADR-0024-post-rest-phase-order.md), [release-process.md](../development/release-process.md), [`.github/MILESTONES.md`](../../.github/MILESTONES.md).

Post-REST phase order (Accepted): WordPress Adapter → Admin → Builder → GraphQL → CLI → Testing & QA → v1.0.

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
WordPress Adapter  ← next

↓

v0.9.0-alpha
Admin UI

↓

v0.10.0-beta
Visual Builder

↓

v0.11.0-beta
GraphQL

↓

v0.12.0-beta
CLI

↓

v1.0.0
Stable
```

Phase 14 (Testing & QA) is a **continuous gate** on every train and a dedicated hardening pass before v1.0 — see [packages/TESTING.md](../../packages/TESTING.md).

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
| **v0.10.0-beta** | Visual Builder | `builder` | Canvas save pipeline + WP admin slot |
| **v0.11.0-beta** | GraphQL | GraphQL package / `api` GraphQL mount | Schema maps from Fields contracts |
| **v0.12.0-beta** | CLI | CLI package | Commands over Core container |
| **v1.0.0** | Stable | — | Phase 14 green; API freeze; migration notes |

Status today: **foundation through `v0.7` (REST) ✅** · next **`v0.8` WordPress Adapter**.

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
| **Beta** | `v0.10`–`v0.12` | Builder + GraphQL + CLI soak |
| **Stable** | `v1.0.0` | SemVer-stable public contracts |

Do not skip trains. Do not claim a version “done” without its testing gates (or explicit N/A in SPEC).
