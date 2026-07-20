# Packages

OpenMeta monorepo packages. Each package README is an **AI/implementation contract**.

Every package must document:

1. Purpose
2. Responsibilities
3. Public APIs
4. Dependencies
5. Extension Points
6. Folder Structure

Implement against those sections — do not invent cross-package couplings that are not listed under Dependencies / Extension Points.

---

## Package map

| Package | Role |
| ------- | ---- |
| [core/](./core/) | Bootstrap, container, events, shared contracts |
| [admin/](./admin/) | WordPress admin screens and admin app shell |
| [api/](./api/) | REST and GraphQL public API layer |
| [database/](./database/) | Schema, migrations, repositories, storage |
| [fields/](./fields/) | Field registry, types, and lifecycle |
| [builder/](./builder/) | Visual field / schema builder |
| [ui/](./ui/) | Shared React / UI component library |
| [validation/](./validation/) | Validation rules and error contracts |
| [security/](./security/) | Capabilities, nonces, authorization helpers |
| [support/](./support/) | Shared helpers and utilities |

```text
packages/
│
├── core/
├── admin/
├── api/
├── database/
├── fields/
├── builder/
├── ui/
├── validation/
├── security/
└── support/
```

---

## Dependency direction (authoritative)

**Rule:** in the stack below, a package may only depend on layers **above** it. It must never import a layer **below** it.

```text
Core
 ↓
Support
 ↓
Database
 ↓
Fields
 ↓
API
 ↓
Admin
 ↓
Builder
```

### Hard rules

- **`core` must never depend on `database`, `fields`, `api`, `admin`, `builder`, or `ui`.**
- Lower layers may depend on upper layers (e.g. `fields` → `database` → `core`).
- `support` is a shared utility package: it must not depend on any other OpenMeta package; `core` and layers below Core may depend on `support`.
- Side packages (`ui`, `validation`, `security`) may depend on `core` / `support` (and others only as listed in their README) — **never** the reverse into `core`.

### Examples

| Allowed | Forbidden |
| ------- | --------- |
| `database` → `core` | `core` → `database` |
| `fields` → `database` | `core` → `fields` |
| `api` → `fields` | `database` → `admin` |
| `builder` → `admin` / `fields` | `core` → `builder` |

Each package README’s **Dependencies** section is binding for implementation.

---

## Purpose

House all production domain code for OpenMeta as independently evolvable packages.

---

## Responsibilities

- Keep package boundaries clear and documented
- Expose only documented public APIs
- Prefer contracts in READMEs before implementation

---

## Public APIs

- Per-package public surfaces documented in each package README
- Root Composer/npm workspace wiring (Phase 01+)

---

## Dependencies

- PHP 8.3+, WordPress 6.8+, Composer, Node 20+ (for UI packages)

---

## Extension Points

- Service providers, registries, and hooks defined per package
- Third-party packages should depend only on documented public APIs

---

## Folder Structure

```text
packages/
├── <package>/
│   ├── src/
│   ├── tests/
│   └── README.md
└── README.md
```

> Pre-alpha: packages are contract stubs until Phase 01 bootstrap begins.
