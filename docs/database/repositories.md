# Repositories

---

# Purpose

The Repository Layer provides the abstraction between the Domain Model and the Storage Layer.

Repositories expose Domain Objects while hiding all persistence details, allowing the framework to change storage strategies without affecting business logic.

Repositories are the only components responsible for retrieving and persisting Aggregate Roots.

---

# Goals

The Repository Layer should provide:

- Storage abstraction
- Domain isolation
- Predictable APIs
- Testability
- Performance
- Extensibility
- Storage independence

---

# Design Principles

The Repository Layer follows these principles:

- Repositories expose Domain Objects.
- Storage is an implementation detail.
- One Repository per Aggregate.
- Repositories depend on Storage Drivers.
- Business logic never performs queries.

---

# Repository Architecture

```text
Application

↓

Repository

↓

Storage Driver

↓

Database
```

The Domain Layer never communicates directly with the database.

---

# Responsibilities

Repositories are responsible for:

- Loading Aggregates
- Persisting Aggregates
- Deleting Aggregates
- Mapping storage records
- Coordinating Storage Drivers

Repositories should never contain business rules.

---

# Repository Types

Typical repositories include:

```text
SchemaRepository

FieldRepository

FieldGroupRepository

ValidationRepository

LocationRepository
```

Each repository owns one Aggregate.

---

# Aggregate Mapping

Repositories convert storage records into Domain Objects.

```text
Database Record

↓

Repository

↓

Domain Object
```

The reverse process occurs during persistence.

---

# Storage Driver Integration

Repositories delegate persistence to Storage Drivers.

```text
Repository

↓

Meta Storage Driver
```

or

```text
Repository

↓

Custom Table Driver
```

Storage implementations remain interchangeable.

---

# Query Responsibilities

Repositories may:

- Find by ID
- Find by UUID
- Find by Slug
- Find by Criteria
- Save
- Delete

Complex query logic should remain inside repositories.

---

# Transactions

Repositories participate in transactions through the Storage Layer.

Repositories should not implement transaction logic themselves.

---

# Caching

Repositories coordinate cache usage.

```text
Repository

↓

Cache

↓

Storage Driver
```

Repositories determine cache lifecycle while Storage Drivers manage persistence.

---

# Error Handling

Repositories should:

- Throw domain-specific exceptions.
- Preserve Aggregate consistency.
- Log persistence failures.
- Prevent partial saves.

---

# Testing

Recommended tests include:

- CRUD operations.
- Aggregate reconstruction.
- Storage Driver compatibility.
- Cache integration.
- Transaction support.
- Performance benchmarks.

Repositories should pass the same contract tests regardless of Storage Driver.

---

# Best Practices

- One Repository per Aggregate.
- Never expose database records.
- Keep repositories storage independent.
- Delegate persistence.
- Keep business logic inside the Domain.
- Use Dependency Injection.

---

# Future Considerations

Potential future enhancements include:

- Query Specifications.
- Read-only repositories.
- CQRS support.
- Event Sourcing integration.
- Repository decorators.
- Distributed repositories.

---

# Summary

The Repository Layer forms the boundary between the Domain Model and the Storage Layer.

By exposing Domain Objects and delegating persistence to interchangeable Storage Drivers, repositories provide OpenMeta with a clean, testable, and storage-agnostic architecture that supports long-term scalability and maintainability.