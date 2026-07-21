# `@openmeta/api`

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Expose OpenMeta resources through a consistent public API layer — WordPress REST and first-class GraphQL — with shared auth, validation, and error shapes.

---

## Responsibilities

- REST route registration and controllers
- GraphQL schema, types, and resolvers (including WPGraphQL integration)
- API authentication/authorization boundaries
- Pagination, filtering, sorting, and structured errors
- Versioned public HTTP contracts

Must not implement field storage or admin UI rendering.

---

## Public APIs

- REST route namespaces and resource controllers
- GraphQL schema entrypoints
- Shared request/response DTOs and error envelopes
- Documented auth and capability requirements per endpoint

---

## Dependencies

- `packages/core`
- `packages/fields`
- `packages/database`
- `packages/validation`
- `packages/security`
- WordPress REST API
- WPGraphQL (optional runtime integration)

---

## Extension Points

- Custom REST routes / controllers
- GraphQL type and field registration
- Serialization transformers
- Auth / capability middleware hooks

---

## Folder Structure

```text
packages/api/
├── src/
│   ├── Rest/
│   ├── GraphQL/
│   ├── Auth/
│   ├── Serialization/
│   └── Errors/
├── tests/
└── README.md
```
