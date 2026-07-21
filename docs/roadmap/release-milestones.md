# Release Plan

Versioned path from Core Bootstrap to stable **v1.0.0**.

**Phase 13** is the authoritative release train. Related: [ROADMAP.md](../../ROADMAP.md), [phase-13-releases.md](./phase-13-releases.md), [release-process.md](../development/release-process.md), [`.github/MILESTONES.md`](../../.github/MILESTONES.md), [packages/TESTING.md](../../packages/TESTING.md).

---

## Release train

```text
v0.1.0-alpha
Core

↓

v0.2.0-alpha
Support

↓

v0.3.0-alpha
Validation

↓

v0.4.0-alpha
Security

↓

v0.5.0-alpha
Database

↓

v0.6.0-alpha
Field Engine

↓

v0.7.0-alpha
REST API

↓

v0.8.0-alpha
Admin

↓

v0.9.0-beta
Builder

↓

v1.0.0
Stable
```

---

## Version → packages

Coding order still applies inside each train. Packages not named in the version title ship as **prerequisites** so dependents stay buildable.

| Version | Theme | Primary package | Also required before exit |
| ------- | ----- | --------------- | ------------------------- |
| **v0.1.0-alpha** | Core | `core` | — |
| **v0.2.0-alpha** | Support | `support` | — |
| **v0.3.0-alpha** | Validation | `validation` | — |
| **v0.4.0-alpha** | Security | `security` | — |
| **v0.5.0-alpha** | Database | `database` | Phase 12 gate on Connection → Relations |
| **v0.6.0-alpha** | Field Engine | `fields` | Built-in types + validate→save→load |
| **v0.7.0-alpha** | REST API | `api` | AuthN/AuthZ + Resources |
| **v0.8.0-alpha** | Admin | `admin` | `ui` kit + screens smoke |
| **v0.9.0-beta** | Builder | `builder` | `wordpress` plugin mount + Phase 12 gates |
| **v1.0.0** | Stable | — | Freeze APIs; migration notes; production readiness |

Status today: **v0.1–v0.9** spines ✅ · next **v1.0.0** Stable.

---

## Exit criteria (every train)

1. Package SPEC(s) for that train implemented (Must not respected).
2. **Phase 12** testing gate: Unit → Integration → WordPress → Performance → Security ([TESTING.md](../../packages/TESTING.md)).
3. `composer ci` green.
4. CHANGELOG entry for the version.
5. GitHub milestone closed for the train.

---

## Maturity stages

| Stage | Versions | Intent |
| ----- | -------- | ------ |
| **Alpha** | `v0.1`–`v0.8` | One spine package per train; APIs may shift |
| **Beta** | `v0.9.0-beta` | Builder + installable WP surface; community soak |
| **Stable** | `v1.0.0` | SemVer-stable public contracts; support policy starts |

Do not skip trains. Do not claim a version “done” without its Phase 12 gates (or explicit N/A in SPEC).

---

## Lifecycle

```text
Planning → Development → Alpha trains → Beta → Stable → Maintenance
```
