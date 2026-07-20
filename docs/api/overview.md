# API Overview

---

# Purpose

The OpenMeta API layer provides a consistent interface for interacting with Schemas, Fields, Domain Objects, and Storage Drivers.

The API is designed around the Domain Model rather than the underlying database, ensuring business logic remains independent of transport protocols and persistence mechanisms.

---

# API Philosophy

OpenMeta follows several core principles.

- API First
- Domain Driven
- Storage Independent
- Consistent Contracts
- Predictable Responses
- Extensible Architecture

Applications should interact with APIs instead of infrastructure whenever possible.

---

# Supported APIs

OpenMeta exposes multiple interfaces.

- PHP API
- REST API
- GraphQL API
- CLI Commands
- Extension APIs

Each interface shares the same domain layer.

---

# Architecture

```text
Application

↓

PHP API

↓

REST API / GraphQL

↓

Repositories

↓

Storage Drivers

↓

Database
```

---

# Request Flow

```text
Request

↓

Authentication

↓

Authorization

↓

Validation

↓

Repository

↓

Storage Driver

↓

Response
```

---

# Core Resources

The API primarily manages:

- Schemas
- Field Groups
- Fields
- Validation Rules
- Relationships
- Storage Configuration
- Extensions
- Packages

---

# Design Principles

The API should:

- Remain transport independent
- Return consistent responses
- Support versioning
- Be extensible
- Hide infrastructure details

---

# Summary

OpenMeta provides a unified API architecture that powers PHP, REST, GraphQL, and future integrations through a common domain-driven foundation.