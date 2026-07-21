# SPEC — `@openmeta/validation`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Complete — Phase 4 / `v0.3.0-alpha`

**Role:** Core shared service — **not** a Fields or API feature. Downstream packages must consume this engine; they must not fork validators.

**Consumers (required / planned):**

| Consumer | Use |
| -------- | --- |
| Fields | Field definitions / value validation |
| API | REST request payloads |
| Database | Schema / migration input checks (when needed) |
| Admin | Forms + settings |
| Builder | Canvas / configuration payloads |
| Import/Export | Row / document validation (future) |
| Plugins / extensions | Custom rules via registry |

---

## Purpose

Own the shared validation pipeline: rule engine, validator, error bag, messages, context, and result — one contract for every OpenMeta boundary that accepts untrusted or structured input.

### Architecture note

Validation is one of the **highest-leverage** packages in the framework. Stability here compounds: Fields, REST, Admin, Builder, Database schema checks, import/export, and third-party extensions all reuse the same rules, ErrorBag, and messages. Prefer extending the registry over inventing per-package validators.

---

## Component map

```text
Validation (façade / provider)
    ↓
Rule Registry + Rule Engine (`Registry/`)
    ↓
Validator (+ ValidationContext)
    ↓
Error Bag → ValidationResult (`Results/`)
    ↓
Messages (MessageBag / MessageResolverInterface)
```

Supporting: **Rule** contracts, built-in **Rules**, **Exceptions**.

---

## Validation

### Responsibilities

- Package entry: service provider, public façade to start a validation run
- Wire Rule Engine + Validator + Messages for consumers
- Expose a single “validate this payload / value” entry used by Fields and API
- Accept arrays, objects, nested / dot-notation attributes

### Public contracts

- `Validation` façade / factory
- `ValidationServiceProvider`
- `ValidationContext`
- `ValidationResult`

### Must not

- Persist data or render UI
- Authorize users (`security`)
- Depend on `fields` or `api` (they depend on validation)

---

## Rule Engine

### Responsibilities

- Define how rules are described, composed, and executed
- Built-in rules + custom rule registration (`extend`)
- Rule options/parameters; side-effect free execution
- Resolve rule ids via registry

### Public contracts

- `RuleInterface`
- `RuleRegistry`, `RuleEngine`

### Must not

- Know about HTTP or field types
- Execute user-supplied PHP as rules (Closures registered by app code are OK)

---

## Validator

### Responsibilities

- Run a rule set (or rules map) against a value or attribute payload
- Orchestrate Rule Engine calls
- Short-circuit where safe
- Produce an Error Bag and immutable Validation Result

### Public contracts

- `Validator` / `ValidatorInterface`

### Must not

- Format user-facing strings itself (Messages owns templates)
- Depend on Fields/API packages

---

## Error Bag

### Responsibilities

- Collect structured validation failures (attribute → errors)
- Each error: field, rule, message, context (params), machine code
- Immutable bag: `add` / `merge` return new instances
- Merge / has / first / all helpers for consumers

### Public contracts

- `ErrorBag`, `ValidationError`

### Must not

- Log full payloads or secrets
- Imply authorization failure

---

## Messages

### Responsibilities

- Resolve human-readable messages from rule failures
- Default message templates; attribute placeholders
- Custom per-attribute / per-rule overrides
- Localization-ready (`MessageResolverInterface`) — catalogs later

### Public contracts

- `MessageBag` (implements `MessageResolverInterface`)

### Must not

- Become a full i18n framework for WordPress admin
- Replace Error Bag structure

---

## Public Contracts (package index)

| API | Class |
| --- | ----- |
| Façade | `OpenMeta\Validation\Validation` |
| Provider | `OpenMeta\Validation\ValidationServiceProvider` |
| Rule | `OpenMeta\Validation\Contracts\RuleInterface` |
| Message resolver | `OpenMeta\Validation\Contracts\MessageResolverInterface`, `Messages\MessageBag` |
| Registry | `OpenMeta\Validation\Registry\RuleRegistry`, `RuleEngine` |
| Validator | `OpenMeta\Validation\Validator\Validator` |
| Context | `OpenMeta\Validation\Context\ValidationContext` |
| Result / errors | `OpenMeta\Validation\Results\ValidationResult`, `ErrorBag`, `ValidationError` |
| Support | `OpenMeta\Validation\Support\DataNormalizer`, `AttributeFormatter` |
| Exceptions | `ValidationException`, `InvalidRuleException`, `ValidationPackageException` |

### Built-in rules

`required`, `required_if`, `nullable`, `string`, `integer`/`int`, `numeric`, `float`, `boolean`/`bool`, `array`, `object`, `email`, `url`, `uuid`, `date`, `datetime`, `enum`, `min`, `max`, `between`, `length`, `in`, `not_in`, `regex`, `starts_with`, `ends_with`, `contains`.

---

## Internal Components

| Component | Location |
| --------- | -------- |
| Validation façade / Provider | `src/` |
| Rule Engine / Registry | `src/Registry/` |
| Rules | `src/Rules/` |
| Validator | `src/Validator/` |
| Context | `src/Context/` |
| Results (incl. ErrorBag) | `src/Results/` |
| Messages | `src/Messages/` |
| Support helpers | `src/Support/` |
| Contracts | `src/Contracts/` |
| Exceptions | `src/Exceptions/` |

---

## Folder Structure

```text
packages/validation/
├── src/
│   ├── Validator/
│   ├── Rules/
│   ├── Registry/
│   ├── Context/
│   ├── Results/
│   ├── Messages/
│   ├── Contracts/
│   ├── Exceptions/
│   └── Support/
├── tests/
├── README.md
└── SPEC.md
```

---

## Dependency Rules

| Direction | Rule |
| --------- | ---- |
| Required | `core`, `support` |
| Forbidden | `fields`, `api`, `admin`, `builder`, `database`, `ui`, `security`, `wordpress` |
| **Required consumers** | `fields`, `api` (must use this Validator) |

---

## Lifecycle

```text
ValidationServiceProvider::register
    ↓
bind Rule Engine, Validator, Messages
    ↓
ValidationServiceProvider::boot
    ↓
register built-in rules → wire Validation façade
```

Runtime:

```text
Validation Request → Resolve Rules → Execute Rules → Collect Errors → ValidationResult
```

---

## Extension Points

- Custom rules via `Validation::extend` / RuleRegistry
- Custom message templates
- Rule-set presets for field types (defined by Fields, executed here)

---

## Performance

- Short-circuit failing rules when safe
- Cheap Error Bag allocation for bulk field saves
- Message resolution only when displaying / serializing errors

---

## Security

- Validation ≠ authorization
- Do not execute user-supplied rule strings as PHP
- Sanitize message interpolation; stable machine codes in Error Bag

---

## Testing Strategy

| Layer | Covers |
| ----- | ------ |
| **Unit** | Built-in rules; Validator; Error Bag; Messages; Result; Context |
| **Integration** | Engine → Validator → Error Bag → Messages |
| **WordPress** | N/A gate (pure PHP) |
| **Performance** | Bulk validate budget |
| **Security** | Unknown rule / unsafe rule strings fail closed |

See [packages/TESTING.md](../../TESTING.md) (Phase 12 gate).

---

## Future Scope

- Async / batched validation
- Client Metadata export for Builder
- Full locale catalogs
- Never: field storage, HTTP routing, or capability checks inside Validation
