# `@openmeta/support`

> Shared, framework-agnostic utilities. **Zero business logic** — pure helpers only.

**Status:** ✅ Complete (Phase 3) · **v0.2.0-alpha**  
**Blueprint:** [SPEC.md](./SPEC.md) · Prompt: [`.ai/prompts/phase-03-support.md`](../../.ai/prompts/phase-03-support.md)

---

## Purpose

Reusable utilities so other packages do not reinvent collections, strings, arrays, paths, filesystem, env, UUID, reflection, or small traits.

---

## Public APIs

| API | Class |
| --- | ----- |
| Arr | `OpenMeta\Support\Arr\Arr` |
| Str | `OpenMeta\Support\Str\Str` |
| Collection | `OpenMeta\Support\Collections\Collection` |
| Path | `OpenMeta\Support\Paths\Path` |
| Filesystem | `OpenMeta\Support\Filesystem\FilesystemInterface` / `LocalFilesystem` |
| Env | `OpenMeta\Support\Environment\Env` |
| UUID | `OpenMeta\Support\Uuid\Uuid` |
| Value object | `OpenMeta\Support\ValueObjects\UuidValue` |
| Contract | `OpenMeta\Support\Contracts\ArrayableInterface` |
| Reflection | `OpenMeta\Support\Reflection\Reflector` |
| Helpers | `OpenMeta\Support\Helpers\Helpers` (`value`, `tap`, `with`, `blank`, `filled`, `classBasename`) |
| Traits | `OpenMeta\Support\Traits\Conditionable` |
| Provider | `OpenMeta\Support\SupportServiceProvider` |

---

## Exit criteria

- Zero business logic (no fields, caps, validation rules, HTTP, WP screens)
- Pure utilities only
- `composer test:support` + `composer ci` green
- No WordPress dependency

---

## Dependencies

- `packages/core` (required)
- PHP 8.3+

Must not depend on domain packages.

---

## Verify

```bash
composer test:support
composer ci
```
