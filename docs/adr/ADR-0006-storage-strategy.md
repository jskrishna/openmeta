# ADR-0006: Storage Strategy

---

# Status

Accepted

---

# Context

OpenMeta manages structured content through Content Types, Fields, Relationships, and Taxonomies.

A consistent storage strategy is required to ensure scalability, performance, maintainability, and compatibility with WordPress.

---

# Problem

Different storage approaches lead to inconsistent querying, difficult migrations, duplicated logic, and reduced interoperability.

---

# Decision

OpenMeta adopts a layered storage architecture.

The storage layer is responsible for:

- Persisting structured data
- Managing relationships
- Supporting indexing
- Abstracting storage implementation
- Providing a consistent access layer

Business logic must remain independent of physical storage.

---

# Alternatives Considered

### Direct Database Access

Rejected because it tightly couples business logic to storage.

### Mixed Storage Strategies

Rejected because inconsistent persistence complicates maintenance.

### In-Memory Storage

Rejected because persistent structured content is required.

---

# Consequences

Positive

- Consistent persistence
- Easier migrations
- Better maintainability
- Future storage flexibility

Negative

- Additional abstraction layer

Trade-offs

- Slight implementation complexity
- Improved long-term scalability

---

# Architecture

```text
Application

↓

Storage Layer

↓

Database

↓

Persistence
```

---

# Impact

Affects:

- Database
- APIs
- Fields
- Relationships
- Import/Export
- Migrations

---

# Related ADRs

- ADR-0004
- ADR-0005
- ADR-0007

---

# Summary

OpenMeta separates business logic from persistence through a dedicated storage layer, ensuring scalable, maintainable, and future-proof data management.