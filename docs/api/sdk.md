# SDK

---

# Purpose

The OpenMeta SDK provides a developer-friendly interface for building applications, extensions, and integrations.

The SDK simplifies interaction with the framework by exposing stable, well-documented abstractions.

---

# Architecture

```text
Application

↓

SDK

↓

PHP API

↓

Repositories

↓

Storage Drivers
```

---

# Responsibilities

The SDK provides access to:

- Schemas
- Fields
- Repositories
- Services
- Events
- Validation
- Storage
- Extensions

---

# Design Principles

The SDK should be:

- Consistent
- Type Safe
- Stable
- Discoverable
- Extensible

---

# Components

Typical SDK components include:

- Client
- Managers
- Builders
- Repositories
- Helpers
- Contracts

---

# Service Container

SDK components should be resolved through the Service Container rather than instantiated directly.

---

# Error Handling

SDK methods should surface meaningful exceptions while hiding implementation details.

---

# Extensibility

Extensions may contribute:

- SDK Services
- Builders
- Managers
- Helpers
- Contracts

The SDK should remain open for extension without modifying core components.

---

# Best Practices

- Prefer SDK abstractions over low-level APIs.
- Resolve services through dependency injection.
- Keep integrations loosely coupled.
- Depend on interfaces rather than implementations.

---

# Summary

The OpenMeta SDK provides a stable, extensible, and developer-friendly layer that simplifies interaction with the framework while preserving clean architectural boundaries.