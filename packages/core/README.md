# `@openmeta/core` — Bootstrap v0.1.0-alpha

> Minimum working framework. **No** database, fields, API, or WordPress integration.

**Status: ✅ Complete** — `v0.1.0-alpha` · [SPEC.md](./SPEC.md) · Release train: [phase-13](../../docs/roadmap/phase-13-releases.md)

**Blueprint:** [SPEC.md](./SPEC.md) · Prompt: [`.ai/prompts/phase-02-core-bootstrap.md`](../../.ai/prompts/phase-02-core-bootstrap.md)

---

## Goal

```text
Application
    ↓
Kernel
    ↓
Container
    ↓
Service Providers
    ↓
Configuration
    ↓
Framework Booted ✅
```

---

## Boot

```php
use OpenMeta\Core\Bootstrap\Bootstrap;
// or: use OpenMeta\Core\Application\Application;

$app = Bootstrap::run(
    ['app' => ['name' => 'OpenMeta']],
    [AppServiceProvider::class],
);

$app->isBooted(); // true
$app->config()->get('app.name');
$app->container();
$app->kernel();
```

### Bootstrap sequence

```text
Load Config → Create Container → Register Core Services
→ Register Providers → Boot Providers → Application Ready
```

`Application::boot()` / `Bootstrapper::boot()` alias `Bootstrap::run()`.

---

## First classes

| Class | Role |
| ----- | ---- |
| `Application` | Ready app + step methods used by Bootstrap |
| `Bootstrap` | **Bootstrap sequence** runner |
| `Kernel` | Bootstrap → Initialize → Ready (providers inside Initialize) |
| `Container` | DI |
| `ServiceProvider` | Provider base / contract |
| `ConfigRepository` | Configuration |
| `EventDispatcher` | Events (`FrameworkBooted`) |
| `Bootstrapper` | Alias of `Bootstrap::run()` |

Docs: [docs/](./docs/) · Build order after this: [docs/build-order.md](./docs/build-order.md)

---

## Out of scope (this milestone)

- Database / storage
- Fields
- REST / GraphQL
- Admin / Builder / WordPress screens

---

## Smoke test

```bash
composer dump-autoload
composer test:core
```

Expected: `OK Core Bootstrap v0.1.0-alpha — Framework Booted`
