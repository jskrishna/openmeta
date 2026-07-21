# `@openmeta/fields`

> **Heart of OpenMeta** — field registry, factory, manager, types, validation, storage contracts, rendering contracts.

**Status:** ✅ Complete (Phase 7) · **v0.6.0-alpha**  
**Blueprint:** [SPEC.md](./SPEC.md) · Package notes: [docs/](./docs/)

---

## Exit criteria

| Criterion | Status |
| --------- | ------ |
| Register field | ✅ `FieldRegistry` / `FieldEngine::register` (aliases + versions) |
| Immutable definitions | ✅ `Definitions\FieldDefinition` |
| Factory / Manager | ✅ `FieldFactory` / `FieldManager` + lifecycle events |
| Save / load | ✅ Manager → serialize → `FieldStorageInterface` (DB adapter) |
| Validate field | ✅ `FieldValidator` → `@openmeta/validation` |
| Conditions / groups | ✅ `Conditions\*` / `Groups\*` |
| Render field | ✅ `FieldRendererInterface` (plain escaped descriptors — **no HTML/UI**) |

---

## Public API

```php
$engine->make('text', 'title', ['required' => true]);
$engine->factory()->makeFromDefinition($definition);
$engine->manager()->save('post', 1, $field, $value);
$engine->validate($field, $value);
$engine->render($field, $value);
```

Prefer **Registry · Factory · Manager · Contracts**. Hide internals.

---

## Built-in types

`text` · `textarea` · `number` · `email` · `url` · `password` · `hidden` · `checkbox` · `radio` · `select` · `multiselect` · `toggle` · `boolean` · `date` · `datetime` · `time` · `color` · `range` · `file` · `image` · `gallery` · `relationship` · `repeater` · `group` · `object`

Complex media / nested types ship as architectural stubs; adapters expand in later packages.

---

## Verify

```bash
php composer.phar test:fields
php composer.phar ci
```
