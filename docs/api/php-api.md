# PHP API

---

# Purpose

The PHP API is the primary interface used by developers building applications, plugins, and extensions with OpenMeta.

It exposes the complete Domain Model while remaining independent of REST, GraphQL, and storage implementations.

---

# Architecture

```text
Application

↓

PHP API

↓

Repositories

↓

Domain

↓

Storage Drivers
```

---

# Responsibilities

The PHP API provides access to:

- Schemas
- Fields
- Field Groups
- Repositories
- Validation
- Storage
- Extensions
- Events

---

# Repository Access

Repositories provide the primary mechanism for interacting with data.

Responsibilities include:

- Create
- Read
- Update
- Delete
- Query

Repositories abstract persistence details.

---

# Services

Application services coordinate business logic.

Examples:

- Schema Manager
- Field Registry
- Validation Service
- Storage Manager

---

# Events

The PHP API dispatches events throughout the application lifecycle.

Examples:

- Schema Created
- Field Saved
- Validation Failed

---

# Dependency Injection

All services should be resolved through the Service Container.

Avoid creating dependencies manually.

---

# Error Handling

The PHP API communicates failures through exceptions and standardized error contracts.

Errors should be explicit and predictable.

---

# Best Practices

- Prefer repositories over direct storage access.
- Resolve services through dependency injection.
- Keep business logic outside controllers.
- Avoid coupling to infrastructure.

---

# Summary

The PHP API is the canonical interface for interacting with OpenMeta and should be the preferred integration point for all server-side development.