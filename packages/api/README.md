# `@openmeta/api`

> Public REST API — Routes, Controllers, Resources, Authentication, Authorization.

**Status:** ✅ Complete (Phase 8) · **v0.7.0-alpha**  
**Blueprint:** [SPEC.md](./SPEC.md)

---

## Namespace

`openmeta/v1`

| Method | Path | Auth |
| ------ | ---- | ---- |
| GET | `/health` | public |
| GET | `/fields/{entityType}/{entityId}/{field}` | AuthN + READ/EDIT/MANAGE |
| PUT | `/fields/{entityType}/{entityId}/{field}` | AuthN + EDIT/MANAGE |

```php
$kernel->handle(new Request('GET', '/openmeta/v1/health'));
```

---

## Stack

```text
REST (Router) → Authentication → Authorization → Controller → Resource
```

---

## Verify

```bash
composer test:api
composer ci
```
