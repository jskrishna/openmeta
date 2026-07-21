# SPEC — `@openmeta/fields`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Complete — Phase 7 / `v0.6.0-alpha`

**Role:** **Heart of OpenMeta.** Expect the most design and implementation time. Everything content-model related flows through this package.

---

## Purpose

Own the field system: registry, factory, manager, immutable definitions, built-in types, groups, conditions, validation bridge, serialization, hydration, storage **contracts** (+ database adapter), rendering **contracts** (+ safe default string renderer), lifecycle, and REST/GraphQL **exposure contracts** — so admin, builder, and API all speak one field model.

Framework-independent. No WordPress Meta API, Gutenberg, admin forms, REST routers, or GraphQL servers inside this package.

---

## Module map

```text
Field Registry
    ↓
Field Factory / Definitions
    ↓
Field Types (+ Groups / Conditions)
    ↓
Validation bridge (@openmeta/validation)
    ↓
Serialization → Storage (contracts)
    ↓
Hydration → Rendering (contracts)
    ↓
Lifecycle + Events (Core dispatcher)
    ↓
REST / GraphQL Support contracts
```

Supporting: **Field Manager**, **Lifecycle orchestration**, **Contracts**, **Service Provider**, **Support**.

**Boundary:** REST Support / GraphQL Support = field exposure contracts (serializers, type maps). HTTP route registration and GraphQL server wiring stay in `@openmeta/api`. HTML admin UI stays in `@openmeta/admin` / `@openmeta/ui`.

---

## Public API (only)

| API | Class |
| --- | ----- |
| Field engine façade | `OpenMeta\Fields\FieldEngine` |
| Field registry | `OpenMeta\Fields\Registry\FieldRegistry` |
| Field factory | `OpenMeta\Fields\Factory\FieldFactory` |
| Field manager | `OpenMeta\Fields\Manager\FieldManager` |
| Contracts | `OpenMeta\Fields\Contracts\*` |
| Provider | `OpenMeta\Fields\FieldsServiceProvider` |

Implementation details (conditions internals, serializers, storage adapters, lifecycle) are injectable but not the primary consumer surface.

---

## Field Registry

### Responsibilities

- Catalog of field types (built-in + custom)
- Register / remove / discover / resolve / has / all — O(1) after boot
- Aliases + versioning support
- Entry point for “what types exist?”
- Dispatch `FieldRegistered`

### Must not

- Static registry / global mutable singleton
- Instantiate HTTP stacks or run migrations
- Own Builder chrome

---

## Field Factory / Definitions

### Responsibilities

- Build field objects from type + settings or immutable `FieldDefinition`
- Validate definition shape (id, name, type, …)
- Dispatch `FieldCreated`

Definitions support: id, name, label, description, type, default, required, validation rules, conditions, visibility, readonly, disabled, metadata, attributes — **immutable** (withers return new instances).

---

## Field Manager / Lifecycle

```text
Register → Build Definition → Validate Configuration → Hydrate
 → Render → Validate Value → Serialize → Store → Load → Deserialize → Return
```

Manager orchestrates validate / save / load / delete with Core events:
`FieldCreated`, `FieldLoaded`, `FieldSaved`, `FieldDeleted`, `FieldValidated`.

---

## Field Types

Built-ins (stubs allowed for media/structured complexity; architecture must support them):

`text` · `textarea` · `number` · `email` · `url` · `password` · `hidden` · `checkbox` · `radio` · `select` · `multiselect` · `toggle` · `boolean` · `date` · `datetime` · `time` · `color` · `range` · `file` · `image` · `gallery` · `relationship` · `repeater` · `group` · `object`

---

## Groups & Conditions

- Groups: registration, nested groups, ordering, visibility, conditional groups
- Conditions: equals, not equals, empty, not empty, greater/less than, in / not in, AND / OR, nesting — extensible via `ConditionInterface`

---

## Validation

Bridge only to `@openmeta/validation`. Do **not** fork a second rule engine.

---

## Serialization / Hydration / Storage / Rendering

| Concern | Rule |
| ------- | ---- |
| Serialization | Pluggable: array / JSON / object (+ registry) |
| Hydration | Generic, storage-independent |
| Storage | **Contracts** + database table adapter; **no** WordPress meta here |
| Rendering | **Contracts only** — default renderer emits escaped plain descriptors, **no HTML/UI**; Admin/UI supply markup |

---

## Folder Structure

```text
packages/fields/
├── src/
│   ├── Conditions/
│   ├── Contracts/
│   ├── Definitions/
│   ├── Events/
│   ├── Exceptions/
│   ├── Factory/
│   ├── Groups/
│   ├── Hydration/
│   ├── Lifecycle/
│   ├── Manager/
│   ├── Registry/
│   ├── Rendering/
│   ├── Serialization/
│   ├── Storage/
│   ├── Support/
│   ├── Types/
│   ├── Field/              # Base Field (kept)
│   ├── Validation/         # Validation bridge (kept)
│   ├── Rest/               # REST exposure contracts (kept)
│   └── GraphQL/            # GraphQL exposure contracts (kept)
├── tests/
├── docs/
├── README.md
└── SPEC.md
```

---

## Dependency Rules

| Direction | Rule |
| --------- | ---- |
| Required | `core`, `support`, `validation`, `security`, `database` |
| Forbidden | `api`, `admin`, `builder`, `wordpress` |
| Consumers | `api`, `admin`, `builder`, `wordpress` (glue) |

---

## Extensibility

Third parties register without core edits: field types, renderers, storage adapters, serializers, hydrators, conditions (via `ConditionInterface`), validators (via Validation package).

---

## Security

- Escape on rendering default path via `@openmeta/security`
- Sanitize on write via type `sanitize()` + Security sanitizer helpers
- Never trust client type settings without schema validation
- Conditions affect presentation — never authorization

---

## Testing Strategy

| Layer | Covers |
| ----- | ------ |
| **Unit** | Registry, definitions, conditions, serializers, exceptions |
| **Integration** | validate → save → load; manager events |
| **WordPress compatibility** | Gate (no WP inside Fields) |
| **Performance** | Registry lookups |
| **Security** | Render escape |

See [packages/TESTING.md](../TESTING.md).

---

## Future Scope

- Richer media / relationship adapters in Wordpress package
- Flexible content layouts
- Never: migration DDL ownership, admin shell, GraphQL server, WP Meta API inside Fields
