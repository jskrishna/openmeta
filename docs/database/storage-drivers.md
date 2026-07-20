# Storage Drivers

---

# Purpose

Storage Drivers provide the abstraction layer between OpenMeta's Domain Model and its persistence mechanisms.

Rather than coupling the framework to WordPress metadata, custom database tables, or any other storage technology, OpenMeta delegates all persistence responsibilities to interchangeable Storage Drivers.

This architecture allows the same Domain Model to work across multiple storage implementations without modification.

---

# Goals

The Storage Driver system should provide:

- Storage independence
- Interchangeable persistence strategies
- High performance
- Repository integration
- Testability
- Extensibility
- Future database support
- Consistent APIs

---

# Design Principles

The Storage Driver system follows these principles:

- Storage is an implementation detail.
- Drivers implement a common contract.
- The Domain never communicates directly with storage.
- Drivers should remain stateless whenever possible.
- Repositories coordinate persistence.
- New drivers should require no changes to existing business logic.

---

# Architecture

```text
Application

↓

Repository

↓

Storage Driver

↓

Persistence Layer
```

The Storage Driver is the only component responsible for interacting with the underlying storage technology.

---

# Driver Architecture

```text
Storage Driver Interface

│

├── Meta Storage Driver

├── Custom Table Driver

├── JSON Driver

├── SQLite Driver

└── External API Driver
```

Every implementation must satisfy the same public contract.

---

# Responsibilities

A Storage Driver is responsible for:

- Create
- Read
- Update
- Delete
- Transactions
- Query execution
- Relationship persistence
- Data mapping

Storage Drivers should not contain business logic.

---

# Supported Drivers

OpenMeta is designed to support multiple storage implementations.

## Meta Storage Driver

Uses native WordPress metadata.

Suitable for:

- Small projects
- Plugin compatibility
- Rapid deployment

---

## Custom Table Driver

Uses dedicated database tables.

Suitable for:

- Large datasets
- Enterprise applications
- High-performance querying

---

## JSON Driver

Stores data in structured JSON files.

Suitable for:

- Development
- Static deployments
- Configuration storage

---

## SQLite Driver

Stores metadata inside SQLite databases.

Suitable for:

- Desktop applications
- Offline environments
- Embedded systems

---

## External Storage Driver

Persists data through external services.

Examples:

- REST APIs
- Cloud Databases
- Headless CMS
- Remote Storage Services

---

# Driver Selection

Applications choose a Storage Driver through configuration.

```text
Configuration

↓

Repository

↓

Selected Driver

↓

Persistence
```

Changing drivers should require no Domain changes.

---

# Repository Integration

Repositories delegate persistence operations.

```text
Repository

↓

Storage Driver

↓

Database
```

Repositories remain completely storage agnostic.

---

# Data Mapping

Storage Drivers convert between:

```text
Domain Object

↓

Storage Format

↓

Database Record
```

and

```text
Database Record

↓

Domain Object
```

The Domain Layer never sees storage-specific structures.

---

# Transactions

Storage Drivers manage:

- Begin Transaction
- Commit
- Rollback

Repositories request transactions without knowing implementation details.

---

# Query Execution

Drivers execute storage-specific queries.

Examples include:

- SQL
- WordPress APIs
- JSON parsing
- REST requests

Query syntax never leaks into higher layers.

---

# Error Handling

If storage operations fail:

- Throw driver-specific exceptions.
- Log diagnostics.
- Roll back active transactions.
- Preserve consistency.

The Repository translates infrastructure errors into domain-friendly exceptions when appropriate.

---

# Performance Considerations

Storage Drivers should:

- Cache frequently accessed data.
- Minimize database queries.
- Batch write operations.
- Support lazy loading.
- Optimize relationship resolution.

Each implementation should follow the performance characteristics of its storage technology.

---

# Testing

Every Storage Driver should pass a shared contract test suite.

Recommended tests include:

- CRUD operations.
- Repository compatibility.
- Transaction support.
- Relationship persistence.
- Error handling.
- Performance benchmarks.

Drivers should behave identically from the Repository's perspective.

---

# Best Practices

- Implement the common Storage Driver contract.
- Keep drivers stateless.
- Avoid business logic.
- Delegate object construction to repositories.
- Handle transactions internally.
- Optimize for the target storage technology.
- Maintain backward compatibility.

---

# Future Considerations

Potential future enhancements include:

- PostgreSQL Driver.
- MongoDB Driver.
- DynamoDB Driver.
- Distributed Storage Drivers.
- Read/Write replication.
- Cloud-native persistence providers.

All future drivers should continue implementing the same Storage Driver interface.

---

# Summary

The Storage Driver system enables OpenMeta to remain completely independent of any specific persistence technology.

By placing all storage responsibilities behind interchangeable drivers, OpenMeta achieves a clean separation between business logic and infrastructure, allowing applications to migrate between WordPress metadata, custom tables, local databases, and future storage technologies without affecting the Domain Model or Repository Layer.