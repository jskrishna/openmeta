# Packages

OpenMeta monorepo packages. Each package has:

| File | Role |
| ---- | ---- |
| **README.md** | Short overview / AI quick contract |
| **SPEC.md** | **Implementation contract** (binding) |

See [BLUEPRINTS.md](./BLUEPRINTS.md) — construction set status (10 package spines).  
See [TESTING.md](./TESTING.md) — **Phase 10** gate after every package (Unit → Integration → WP → Performance).

Implement against **SPEC.md**. Do not invent cross-package couplings outside **Dependency Rules**.

### Why SPEC (not 100 pages of docs)

```text
“Build Database package”
        ↓
packages/database/SPEC.md
        ↓
kya banana · kya nahi · dependencies · public API · testing
```

One SPEC per package = enough context for Cursor (or any contributor) to implement without dumping the whole docs tree.

---

## SPEC.md required sections

Every `SPEC.md` must use this structure (see [SPEC.TEMPLATE.md](./SPEC.TEMPLATE.md)):

1. Purpose  
2. Responsibilities  
3. Public Contracts  
4. Internal Components  
5. Folder Structure  
6. Dependency Rules  
7. Lifecycle  
8. Extension Points  
9. Performance  
10. Security  
11. Testing Strategy  
12. Future Scope  

README may stay shorter; SPEC is the source of truth for implementation.

---

## Package map

| Package | Role | Blueprint |
| ------- | ---- | --------- |
| [core/](./core/) | Bootstrap, container, events, shared contracts | [SPEC](./core/SPEC.md) |
| [support/](./support/) | Shared helpers and utilities | [SPEC](./support/SPEC.md) |
| [validation/](./validation/) | Validation rules and error contracts | [SPEC](./validation/SPEC.md) |
| [security/](./security/) | Capabilities, nonces, authorization helpers | [SPEC](./security/SPEC.md) |
| [database/](./database/) | Schema, migrations, repositories, storage | [SPEC](./database/SPEC.md) |
| [fields/](./fields/) | Field registry, types, and lifecycle | [SPEC](./fields/SPEC.md) |
| [rest/](./rest/) | Framework REST infrastructure (WP-independent) | [SPEC](./rest/SPEC.md) |
| [api/](./api/) | Application API surface (field routes; mounts REST) | [SPEC](./api/SPEC.md) |
| [ui/](./ui/) | Shared React / UI component library | [SPEC](./ui/SPEC.md) |
| [admin/](./admin/) | WordPress admin screens and admin app shell | [SPEC](./admin/SPEC.md) |
| [builder/](./builder/) | Visual field / schema builder | [SPEC](./builder/SPEC.md) |

```text
packages/
│
├── core/        README.md  SPEC.md
├── support/     README.md  SPEC.md
├── validation/  README.md  SPEC.md
├── security/    README.md  SPEC.md
├── database/    README.md  SPEC.md
├── fields/      README.md  SPEC.md
├── rest/        README.md  SPEC.md
├── api/         README.md  SPEC.md
├── ui/          README.md  SPEC.md
├── admin/       README.md  SPEC.md
└── builder/     README.md  SPEC.md
```

---

## Dependency rules (authoritative)

```text
Core
│
├── No dependencies on other OpenMeta packages
│
└── Every other package depends on Core
```

### Dependency graph

```text
Core
│
├── Support
├── Database
├── Validation
├── Security
├── API
├── Fields
├── UI
├── Admin
└── Builder
```

### Hard rules

1. **`core` depends on zero other OpenMeta packages** (not even `support`).
2. **Every other package must depend on `core`** (directly).
3. Sibling packages may depend on each other only when their own README allows it (e.g. `fields` → `database`, `api` → `fields`).
4. **Nothing may depend upward into a forbidden direction that pulls `core` toward domain packages** — i.e. never `core` → `fields` / `database` / `api` / `admin` / `builder` / `ui` / …

### Examples

| Allowed | Forbidden |
| ------- | --------- |
| `support` → `core` | `core` → `support` |
| `database` → `core` | `core` → `database` |
| `fields` → `core` + `database` | `core` → `fields` |
| `rest` → `core` + `fields` (+ upstream) | `rest` → `admin` / `wordpress` |
| `api` → `core` + `fields` (+ `rest`) | `database` → `admin` (unless README explicitly allows) |
| `builder` → `core` + `admin` + `fields` | `core` → `builder` |

Each package README’s **Dependencies** section is binding for implementation.

---

## Coding order

**Actual implementation order.** One package at a time. Prompt = that package’s `SPEC.md`.

```text
Core
    ↓
Support
    ↓
Validation
    ↓
Security
    ↓
Database
    ↓
Fields
    ↓
Rest (framework HTTP)
    ↓
API (application routes)
    ↓
UI
    ↓
Admin
    ↓
Builder
```

| # | Package | Status | Contract |
| - | ------- | ------ | -------- |
| 1 | **Core** | ✅ `v0.1.0-alpha` | [SPEC](./core/SPEC.md) |
| 2 | **Support** | ✅ | [SPEC](./support/SPEC.md) |
| 3 | Validation | ✅ | [SPEC](./validation/SPEC.md) |
| 4 | Security | ✅ | [SPEC](./security/SPEC.md) |
| 5 | Database | ✅ | [SPEC](./database/SPEC.md) |
| 6 | Fields | ✅ | [SPEC](./fields/SPEC.md) |
| 7 | **Rest** | ✅ `v0.7.0-alpha` | [SPEC](./rest/SPEC.md) |
| 8 | API | ✅ (app layer) | [SPEC](./api/SPEC.md) |
| 9 | UI | Waiting | [SPEC](./ui/SPEC.md) |
| 10 | Admin | Waiting | [SPEC](./admin/SPEC.md) |
| 11 | Builder | Waiting | [SPEC](./builder/SPEC.md) |

### How to build

```text
Open packages/<name>/SPEC.md → implement → tests → composer ci
```

Do not skip ahead unless Dependency Rules allow it. Details: [core/docs/build-order.md](./core/docs/build-order.md).

Detail: [core/docs/build-order.md](./core/docs/build-order.md).

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
