# `@openmeta/database`

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Provide storage abstraction, schema/migrations, repositories, and persistence for OpenMeta structured content — independent of field UI and HTTP layers.

---

## Responsibilities

- Storage drivers and repository implementations
- Schema management and migrations
- Custom tables vs meta storage strategy
- Relationship persistence and indexing
- Query helpers used by domain services

Must not own field rendering, validation rule definitions, or HTTP controllers.

---

## Public APIs

- Repository interfaces
- Migration runner contracts
- Storage driver interfaces
- Query / criteria helpers documented for package consumers

---

## Dependencies

- `packages/core`
- WordPress `$wpdb` / database APIs
- MySQL / MariaDB

May be used by `fields`, `api`, and other domain packages. Must not depend on `admin`, `ui`, or `builder`.

---

## Extension Points

- Custom storage drivers
- Migration providers
- Repository decorators / caching layers
- Index strategy plugins

---

## Folder Structure

```text
packages/database/
├── src/
│   ├── Repositories/
│   ├── Migrations/
│   ├── Schema/
│   ├── Drivers/
│   └── Query/
├── tests/
└── README.md
```
