# SPEC — `@openmeta/support`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Complete — Phase 3 / `v0.2.0-alpha`

**Role:** Common utilities for almost every other OpenMeta package. Keep lean; no domain logic.

---

## Purpose

Provide shared, framework-agnostic utilities so `validation`, `security`, `database`, `fields`, `api`, `ui`, `admin`, and `builder` do not reinvent collections, strings, paths, filesystem, env, UUID, reflection, or small traits.

---

## Component map

```text
Support
├── Collections
├── Helpers
├── Str
├── Arr
├── Filesystem
├── Paths
├── Environment
├── UUID
├── Reflection
└── Traits
```

Spine (implementation / dependency awareness within the package):

```text
Arr
    Responsibilities
        ↓
Str
    Responsibilities
        ↓
Collections
    Responsibilities
        ↓
Paths
    Responsibilities
        ↓
Filesystem
    Responsibilities
        ↓
Environment
    Responsibilities
        ↓
UUID
    Responsibilities
        ↓
Reflection
    Responsibilities
        ↓
Helpers
    Responsibilities
        ↓
Traits
    Responsibilities
```

---

## Collections

### Responsibilities

- Fluent / iterable collection wrapper over arrays when Arr is not enough
- Map, filter, reduce, pluck, groupBy-style operations
- Stay immutable-by-default or document mutation clearly

### Public contracts

- `Collection` (or equivalent) public API

### Must not

- Persist to DB or call WordPress
- Become a second query builder (`database`)

---

## Helpers

### Responsibilities

- Small cross-cutting functions that do not fit Arr/Str/Path alone
- Thin wrappers that compose other Support modules
- Document every helper; reject “misc dump” growth

### Public contracts

- Named helper functions/classes listed before merge

### Must not

- Hide domain rules (fields, caps, validation)
- Grow without SPEC update

---

## Str

### Responsibilities

- String utilities (starts/ends, limit, snake/camel, encoding-safe ops as needed)
- Consistent multibyte behavior where claimed

### Public contracts

- `Str` helper API

### Must not

- Own HTML sanitization policy (`security` / display layers)
- Own i18n message catalogs (`validation` messages stay there)

---

## Arr

### Responsibilities

- Array access and transforms (only/except, pluck, get/set, wrap, flatten as needed)
- Dot-notation helpers if required by multiple packages
- Allocation-conscious hot-path defaults

### Public contracts

- `Arr` helper API

### Must not

- Replace Collections for heavy fluent pipelines (point callers to Collections)

---

## Filesystem

### Responsibilities

- Read/write/exists/delete for local files when packages need it
- Safe defaults; explicit error behavior

### Public contracts

- Filesystem interface / local driver

### Must not

- Cloud storage product (S3, etc.) unless Future Scope + ADR
- Path traversal footguns — always go through Paths

---

## Paths

### Responsibilities

- Join / normalize paths cross-platform
- Resolve package-relative and base paths predictably

### Public contracts

- `Path` / Paths helper API

### Must not

- Treat remote URLs as filesystem paths
- Ship unsafe “resolve user input as path” without validation docs

---

## Environment

### Responsibilities

- Read environment variables / `.env`-style values when OpenMeta runs outside pure WP constants
- Typed getters (string, bool, int) with defaults
- Document precedence vs `wp-config` / Core config

### Public contracts

- `Environment` / `Env` API

### Must not

- Commit secrets; log env values
- Replace Core `ConfigRepository` (env feeds config; config owns nested app settings)

---

## UUID

### Responsibilities

- Generate RFC-appropriate UUIDs (version choice documented at implementation)
- Parse / validate UUID strings if needed by consumers

### Public contracts

- `Uuid` / UUID factory API

### Must not

- Depend on random extensions without fallback policy documented
- Store UUIDs (persistence is `database`)

---

## Reflection

### Responsibilities

- Small reflection helpers for container-adjacent or attribute discovery use cases
- Cache reflection results where used on hot paths

### Public contracts

- Reflection helper API (narrow surface)

### Must not

- Full DI auto-wiring engine (Core Container future scope)
- Execute arbitrary user PHP

---

## Traits

### Responsibilities

- Tiny reusable traits (e.g. macroable-style only if justified; singleton-in-tests helpers — names fixed later)
- Prefer composition; traits are last resort

### Public contracts

- Documented traits list in this SPEC before merge

### Must not

- God traits that pull in domain behavior
- Traits that require WordPress

---

## Public Contracts (package index)

| API | Class |
| --- | ----- |
| Arr | `OpenMeta\Support\Arr\Arr` |
| Str | `OpenMeta\Support\Str\Str` |
| Collection | `OpenMeta\Support\Collections\Collection` |
| Path | `OpenMeta\Support\Paths\Path` |
| Filesystem | `OpenMeta\Support\Filesystem\FilesystemInterface`, `LocalFilesystem` |
| Env | `OpenMeta\Support\Environment\Env` |
| UUID | `OpenMeta\Support\Uuid\Uuid` (v4 via `random_bytes`) |
| Value object | `OpenMeta\Support\ValueObjects\UuidValue` |
| Contract | `OpenMeta\Support\Contracts\ArrayableInterface` |
| Reflection | `OpenMeta\Support\Reflection\Reflector` |
| Helpers | `OpenMeta\Support\Helpers\Helpers` — `value`, `tap`, `with`, `blank`, `filled`, `classBasename` |
| Traits | `OpenMeta\Support\Traits\Conditionable` |
| Provider | `OpenMeta\Support\SupportServiceProvider` |
| Exceptions | `SupportException`, `InvalidPathException`, `FilesystemException`, `UuidException` |

---

## Internal Components

| Component | Location |
| --------- | -------- |
| Collections | `src/Collections/` |
| Arr | `src/Arr/` |
| Str | `src/Str/` |
| Filesystem | `src/Filesystem/` |
| Paths | `src/Paths/` (class `Path`; folder plural matches module name) |
| Environment | `src/Environment/` |
| UUID | `src/Uuid/` |
| Reflection | `src/Reflection/` |
| Traits | `src/Traits/` |
| Helpers | `src/Helpers/` |
| ValueObjects | `src/ValueObjects/` |
| Contracts | `src/Contracts/` |
| Exceptions | `src/Exceptions/` |

---

## Folder Structure

```text
packages/support/
├── src/
│   ├── Collections/
│   ├── Arr/
│   ├── Str/
│   ├── Filesystem/
│   ├── Paths/          # Path utilities (SPEC name; not Path/)
│   ├── Environment/
│   ├── Uuid/           # UUID utilities (SPEC namespace Uuid)
│   ├── Reflection/
│   ├── Traits/
│   ├── Helpers/
│   ├── ValueObjects/
│   ├── Contracts/
│   └── Exceptions/
├── tests/
├── README.md
└── SPEC.md
```

---

## Dependency Rules

| Direction | Rule |
| --------- | ---- |
| Required | `core` |
| Forbidden | `database`, `fields`, `api`, `admin`, `builder`, `ui`, `validation`, `security` |
| Consumers | **Almost every other package** may depend on `support` in addition to `core` |

Hard rule: Support never depends on domain packages.

---

## Lifecycle

```text
SupportServiceProvider::register
    ↓
bind filesystem / env / uuid factories if needed
    ↓
SupportServiceProvider::boot
    ↓
usually no-op
```

Most APIs are static or pure — available as soon as Composer autoload + provider registration allow.

---

## Extension Points

- Prefer PRs for new helpers over hooks
- Filesystem driver interface only if a second driver is approved in Future Scope
- Any hook must be listed here before shipping

---

## Performance

- Arr/Str/Collections: avoid needless copies on hot paths
- Reflection: cache
- Filesystem: no chatty I/O in tight loops
- UUID: document CSPRNG source

---

## Security

- Never log Environment secrets
- Paths + Filesystem: prevent traversal; reject null bytes
- Reflection: no evaluating user strings as code
- UUID: use secure random where identity matters

---

## Testing Strategy

| Layer | Covers |
| ----- | ------ |
| **Unit** | Arr, Str, Collections, Paths, UUID, Env getters; Filesystem (temp dirs); Reflection; Traits |
| **Integration** | Optional — Helpers + Paths + Filesystem together |
| **WordPress compatibility** | N/A if pure PHP modules (no WP bootstrap) |
| **Performance** | Optional hot-path Arr/Str budgets |

See [packages/TESTING.md](../../TESTING.md) (Phase 12 gate).

---

## Future Scope

- Additional filesystem drivers (ADR)
- Lazy Collection for large datasets
- Carbon-like dates only with clear multi-package demand
- Never: validation rules, capabilities, field types, HTTP, admin UI
