# SPEC — `@openmeta/wordpress`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Complete — `v0.8.0-alpha`

**Host:** WordPress adapter glue. Bridges framework packages into WP lifecycle without redefining domain logic.

---

## Purpose

Mount OpenMeta inside WordPress — plugin bootstrap, hooks/filters, meta adapters, REST bridges, capabilities, assets, localization, lifecycle — without owning field types, validation rules, admin UI HTML, or builder canvas logic.

WordPress is an **adapter**, not the framework.

---

## Module map

```text
Plugin Bootstrap / Configuration
    ↓
Hooks / Filters (HookManager, FilterManager, bridges)
    ↓
Meta Adapters (post / user / term / comment / options)
    ↓
Post Types / Taxonomies
    ↓
Users / Settings / Capabilities / Nonces
    ↓
Assets / Localization
    ↓
Lifecycle / Events
    ↓
REST (Api RestBridge + Framework RestBridge)
    ↓
Admin Pages / Gutenberg (legacy BC bridges)
```

---

## Plugin Bootstrap

### Responsibilities

- Plugin entry constants + environment checks (PHP / WP versions)
- Create Application via `Bootstrap::run` with full provider stack (includes `RestServiceProvider` before `ApiServiceProvider`)
- Idempotent boot; fail closed with admin notice when requirements fail
- Activation / deactivation hooks (capabilities seed, version upgrade option)

### Must not

- Business logic / field registration / REST handlers in the entry file
- Bypass `security` for mutations

---

## Hooks / Filters

### Responsibilities

- Register WordPress `add_action` / `add_filter` via runtime adapters
- `HookManager` / `FilterManager` wrap runtime for register/remove/priority
- Document stable action names (`openmeta_*`)

### Must not

- Replace the Core EventDispatcher as the sole internal bus

---

## Meta

### Responsibilities

- `PostMetaAdapter`, `UserMetaAdapter`, `TermMetaAdapter`, `CommentMetaAdapter`, `OptionsAdapter`
- `WordPressFieldStorage` implements `FieldStorageInterface` for Fields package
- All reads/writes via `WordPressRuntimeInterface` (testable)

### Must not

- Direct `$wpdb` access
- Field-type engine logic

---

## Post Types / Taxonomies

### Responsibilities

- `PostTypeRegistrar`, `TaxonomyRegistrar` register from array definitions via runtime
- Array runtime records registrations for CI

### Must not

- Hardcoded CPTs in adapter code

---

## Users / Settings

### Responsibilities

- `UserAdapter` — current user id + capability checks (fail closed)
- `SettingsAdapter` — register groups, validate on save via Validation package (no HTML)

### Must not

- Admin settings screens (Admin package owns UI)

---

## Capabilities / Nonces

### Responsibilities

- Seed OpenMeta permissions on activation via `CapabilityRegistrar`
- Rebind Security `CapabilityCheckerInterface` / `NonceHandlerInterface` when WP APIs exist
- `WordPressNonceHandler` lives under `Nonces/` (`Nonce/` namespace alias for BC)

### Must not

- Parallel authz outside `security`

---

## Assets / Localization

### Responsibilities

- `AssetManager` — register/enqueue scripts and styles via runtime (versioned, no inline assets)
- `LocalizationAdapter` — `load_plugin_textdomain` for text domain `openmeta`

### Must not

- Inline admin HTML or builder assets with business logic

---

## Lifecycle / Events

### Responsibilities

- `LifecycleManager` — activate/deactivate/admin/rest hooks
- `UpgradeManager` — non-destructive version option compare
- Dispatch Core events: `PluginActivated`, `PluginDeactivated`, `AdminLoaded`, `RestInitialized`

### Must not

- Destructive migrations on upgrade without explicit future ADR

---

## REST

### Responsibilities

- `Rest\RestBridge` — mounts `@openmeta/api` Router + RestKernel on `rest_api_init`
- `Rest\FrameworkRestBridge` — mounts `@openmeta/rest` Router + RestKernel when bound
- Filterable namespace via `openmeta_rest_namespace`

### Must not

- Duplicate route definitions outside Api / Rest packages

---

## Admin Pages / Gutenberg (BC)

### Responsibilities

- Map `@openmeta/admin` menus → WP admin pages
- Register block type metadata

### Must not

- Own screen content, field engine, or Gutenberg UI expansions

---

## Public Contracts (package index)

| API | Component |
| --- | --------- |
| `Plugin::boot()` / requirements | Plugin Bootstrap |
| `HookManagerInterface` / `ActionBridge` | Hooks |
| `FilterManagerInterface` / `FilterBridge` | Filters |
| `MetaAdapterInterface` / `WordPressFieldStorage` | Meta |
| `PostTypeRegistrar` / `TaxonomyRegistrar` | CPT / Taxonomy |
| `UserAdapter` | Users |
| `SettingsAdapter` | Settings |
| `AssetManagerInterface` | Assets |
| `LifecycleManagerInterface` | Lifecycle |
| `FrameworkRestBridge` / `RestBridge` | REST |
| `WordPressRuntimeInterface` | Runtime adapter |
| `AdapterRegistry` | Named adapter façade |

---

## Folder Structure

```text
packages/wordpress/
├── src/
│   ├── Adapters/
│   ├── Assets/
│   ├── Bootstrap/
│   ├── Capabilities/
│   ├── Configuration/
│   ├── Contracts/
│   ├── Events/
│   ├── Exceptions/
│   ├── Filters/
│   ├── Hooks/
│   ├── Lifecycle/
│   ├── Localization/
│   ├── Meta/
│   ├── Nonces/
│   ├── Nonce/          (BC alias)
│   ├── PostTypes/
│   ├── Providers/
│   │   └── WordpressServiceProvider.php
│   ├── Rest/           (Api + Framework REST bridges)
│   ├── Runtime/
│   ├── Settings/
│   ├── Support/
│   ├── Taxonomies/
│   ├── Users/
│   ├── Admin/          (BC menu bridge)
│   ├── Gutenberg/      (BC block metadata)
│   ├── Plugin/
│   └── WordpressServiceProvider.php  (deprecated BC alias)
├── tests/
├── docs/
├── README.md
└── SPEC.md
```

Root plugin file: `openmeta.php` (repo root).

---

## Dependency Rules

| Direction | Rule |
| --------- | ---- |
| Required | `core`, `support`, `validation`, `security`, `database`, `fields`, `rest`, `api`, `ui`, `admin`, `builder` |
| Forbidden | Field-type engine, raw `$wpdb` schema, GraphQL server, Admin UI HTML, Builder canvas logic |
| Consumers | WordPress sites / the plugin entry |

May mount admin/builder providers for existing bridges; this package **Must not** own their domain logic.

---

## Lifecycle

```text
openmeta.php
    ↓
Requirements check
    ↓
WordpressServiceProvider::register → Runtime, adapters, bridges, Plugin
    ↓
Plugin::boot → Bootstrap::run(providers) → register WP hooks
    ↓
plugins_loaded / admin_menu / rest_api_init / init (blocks)
```

---

## Testing Strategy

| Layer | Covers |
| ----- | ------ |
| **Unit** | Meta adapters; Hook/Filter managers; AssetManager; Lifecycle; PostTypeRegistrar; FrameworkRestBridge |
| **Integration** | Plugin boot with Array runtime; Admin pages; REST route sync |
| **WordPress Compatibility** | Native runtime no-ops safely when WP missing |
| **Performance** | Boot hook registration budget |
| **Security** | Capability fail-closed; nonce bridge |

See [packages/TESTING.md](../TESTING.md).

---

## Future Scope

- Multisite network admin
- Full block editor JS package
- WP-CLI commands
- Never: duplicate Fields / Api / Admin / Rest domain logic here
