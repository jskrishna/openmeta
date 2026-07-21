# `@openmeta/validation`

> Shared validation engine for the whole framework. **Not** a Fields/API feature. **Validation ≠ authorization.**

**Status:** ✅ Complete (Phase 4) · **v0.3.0-alpha**  
**Blueprint:** [SPEC.md](./SPEC.md) · Prompt: [`.ai/prompts/phase-04-validation.md`](../../.ai/prompts/phase-04-validation.md)

---

## Purpose

One Rule Engine + Validator + Error Bag + Messages stack for array/object payloads (nested / dot notation, custom rules, conditional `required_if`).

**Highest-leverage core service** — reused by Fields, REST, Admin, Builder, Database schema checks, import/export, and future plugin extensions. Do not fork validators in those packages; extend this registry instead.

---

## Public APIs

| API | Class |
| --- | ----- |
| Façade | `OpenMeta\Validation\Validation` |
| Validator | `OpenMeta\Validation\Validator\Validator` |
| Context / Result | `ValidationContext`, `ValidationResult`, `ErrorBag`, `ValidationError` |
| Registry | `RuleRegistry`, `RuleEngine` |
| Support | `DataNormalizer`, `AttributeFormatter` |
| Rules | `OpenMeta\Validation\Rules\*` + `RuleInterface` |
| Messages | `MessageBag` (`MessageResolverInterface`) |
| ErrorBag | `ErrorBag`, `ValidationError` |
| Exceptions | `ValidationException`, `InvalidRuleException` |
| Provider | `OpenMeta\Validation\ValidationServiceProvider` |

```php
use OpenMeta\Validation\Validation;

Validation::make($arrayOrObject, [
    'email' => 'required|email',
    'age' => 'integer|min:18',
    'id' => 'uuid',
])->validate();

$result = Validation::make($data, $rules)->result();

Validation::extend('odd', fn ($attr, $value) => ((int) $value) % 2 === 1);
```

---

## Exit criteria

- ✅ Validate arrays / objects / nested data
- ✅ Built-in rule set per SPEC
- ✅ Custom rules (`Validation::extend` / `RuleInterface`)
- ✅ `composer test:validation` + quality gates green

---

## Verify

```bash
composer test:validation
composer ci
```
