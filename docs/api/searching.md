# Searching

---

# Purpose

The OpenMeta Search System enables developers to locate resources efficiently across Schemas, Fields, Relationships, and other domain entities.

Searching should provide a consistent interface regardless of whether data is stored in WordPress Meta, Custom Tables, SQLite, or other Storage Drivers.

The search system must remain storage-independent and repository-driven.

---

# Search Architecture

```text
Client

↓

Search Request

↓

Search Builder

↓

Repository

↓

Storage Driver

↓

Database

↓

Results
```

---

# Search Objectives

The Search System should:

- Be storage independent
- Support multiple resource types
- Return deterministic results
- Scale to large datasets
- Remain extensible

---

# Searchable Resources

OpenMeta supports searching across:

- Schemas
- Field Groups
- Fields
- Relationships
- Extensions
- Packages
- Settings

Future resources may also participate in the search system.

---

# Search Types

Supported search strategies include:

- Exact Match
- Partial Match
- Prefix Search
- Suffix Search
- Keyword Search
- Full-text Search
- Fuzzy Search (future)

Repositories determine how these searches are translated into storage operations.

---

# Search Builder

Search criteria should be composed through a Search Builder rather than database-specific queries.

Responsibilities include:

- Query composition
- Filter integration
- Sorting integration
- Pagination support

---

# Repository Integration

Repositories execute search operations while hiding storage implementation details.

Business logic should never communicate directly with database queries.

---

# Performance

Recommended optimizations:

- Index searchable fields.
- Cache common searches.
- Limit result sizes.
- Avoid unnecessary relationships.
- Support incremental loading.

---

# Extensibility

Extensions may:

- Register searchable resources
- Add custom search strategies
- Contribute search providers
- Customize ranking algorithms

---

# Best Practices

- Keep searches storage independent.
- Validate search parameters.
- Paginate large result sets.
- Cache expensive queries.
- Avoid exposing storage-specific syntax.

---

# Summary

The OpenMeta Search System provides a flexible, extensible, and storage-independent mechanism for locating resources while maintaining consistent behavior across PHP, REST, and GraphQL APIs.