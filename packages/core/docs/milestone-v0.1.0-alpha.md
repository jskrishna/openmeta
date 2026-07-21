# Milestone: Core Bootstrap v0.1.0-alpha

**Status: ✅ Complete**

## Goal

Ship a **minimum working framework** boot path:

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

## Completed checklist

| Area | Status |
| ---- | ------ |
| Core Bootstrap | ✅ |
| Container | ✅ |
| Config | ✅ |
| Kernel | ✅ |
| Providers | ✅ |
| Application | ✅ |
| Bootstrap sequence | ✅ |
| Core Tests (PHPUnit) | ✅ |
| CI (Composer → PHPStan → PHPCS → PHPUnit) | ✅ |

## In scope (shipped)

- Contracts, Application, Kernel (`Bootstrap → Initialize → Ready`)
- Container (bind / singleton / resolve / aliases)
- Config (`app` / `database` / `api` files + repository)
- Service providers (Register → Boot)
- Bootstrap sequence → Application Ready
- `FrameworkBooted` event
- PHPUnit + smoke + GitHub Actions CI

## Out of scope (later packages)

- Database, Fields, API, GraphQL, WordPress integration, Admin, Builder

## Verify

```bash
composer ci
composer test:core
```

## After this milestone

```text
v0.1.0-alpha (Core) ✅
    ↓
Support Package
    ↓
Validation
    ↓
Security
    ↓
Database
    ↓
Fields
    ↓
API
    ↓
Admin
    ↓
Builder
    ↓
v1.0.0
```

See [build-order.md](./build-order.md) and [GitHub milestones process](../../../.github/MILESTONES.md).
